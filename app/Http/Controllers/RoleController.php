<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\RoleResource;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class RoleController extends Controller
{
    /**
     * Display a listing of roles with optional search, sorting, pagination.
     */
    public function index(Request $request)
    {
        try {
            $validated = $request->validate([
                'search'    => ['nullable', 'string'],
                'orderBy'   => ['nullable', 'in:id,name,slug'],
                'sortedBy'  => ['nullable', 'in:asc,desc'],
                'limit'     => ['nullable', 'integer', 'min:1'],
                'skip'      => ['nullable', 'integer', 'min:0'],
            ]);

            // Build query with eager loaded permissions
            $query = Role::with('permissions')
                ->when(!empty($validated['search']), fn($q) => 
                    $q->where('name', 'like', $validated['search'].'%')
                )
                ->when(!empty($validated['orderBy']), function ($q) use ($validated) {
                    $q->orderBy($validated['orderBy'], $validated['sortedBy'] ?? 'asc');
                }, fn($q) => $q->orderByDesc('id'));

            // Get total count before pagination
            $total = (clone $query)->count();

            if (!empty($validated['skip'])) {
                $query->skip($validated['skip']);
            }
            if (!empty($validated['limit'])) {
                $query->take($validated['limit']);
            }

            // Execute query and transform
            $roles = $query->get();

            return response()->json([
                'status' => 'success',
                'total'  => $total,
                'data'   => RoleResource::collection($roles),
            ], 200);

        } catch (ValidationException $e) {
            // Validation errors
            return response()->json([
                'status' => 'validation_error',
                'errors' => $e->errors(),
            ], 422);
        } catch (Exception $e) {
            // Log unexpected errors
            Log::error('Role index error', ['error' => $e->getMessage()]);

            return response()->json([
                'status'  => 'error',
                'message' => 'Failed to retrieve roles.',
            ], 500);
        }
    }

    /**
     * Show a single role by slug with its permissions.
     */
    public function show(Request $request)
    {
        $request->validate([
            'slug' => 'required|string|exists:roles,slug',
        ]);

        try {
            $role = Role::with('permissions')->where('slug', $request->slug)->firstOrFail();

            return response()->json(new RoleResource($role), 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'status' => 'not_found',
                'message' => 'Role not found.',
            ], 404);
        } catch (Exception $e) {
            Log::error('Role show error', ['error' => $e->getMessage()]);

            return response()->json([
                'status'  => 'error',
                'message' => 'Failed to retrieve the role.',
            ], 500);
        }
    }

    /**
     * Store a newly created role with permissions.
     */
    public function store(Request $request)
    {
        // Validate role name uniqueness and permissions array
        $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:roles,name'],
            'permissions' => ['required', 'array', 'min:1'],
            'permissions.*' => ['string'],
        ]);

        DB::beginTransaction();

        try {
            // Create role with UUID slug
            $role = new Role;
            $role->slug = (string) Str::uuid();
            $role->name = $request->name;
            $role->save();

            // Find or create permissions and collect their slugs
            $permissionSlugs = collect($request->permissions)->map(function ($permissionName) {
                $permission = Permission::firstOrCreate(
                    ['name' => $permissionName],
                    ['slug' => (string) Str::uuid()]
                );
                return $permission->slug;
            })->all();

            // Attach permissions to the role
            $role->permissions()->attach($permissionSlugs);

            DB::commit();

            return response()->json([
                'status'  => 'success',
                'message' => 'Role created successfully.',
                'data'    => new RoleResource($role->fresh('permissions')),
            ], 201);

        } catch (Exception $e) {
            DB::rollBack();

            Log::error('Role store error', ['error' => $e->getMessage()]);

            return response()->json([
                'status'  => 'error',
                'message' => 'Failed to create role.',
            ], 500);
        }
    }

    /**
     * Update an existing role and sync permissions.
     */
    public function update(Request $request)
    {
        $request->validate([
            'slug' => ['required', 'string', 'exists:roles,slug'],
            'name' => ['required', 'string', 'max:255', Rule::unique('roles')->ignore($request->slug, 'slug')],
            'permissions' => ['required', 'array', 'min:1'],
            'permissions.*' => ['string'],
        ]);

        DB::beginTransaction();

        try {
            $role = Role::where('slug', $request->slug)->firstOrFail();

            // Update role name
            $role->name = $request->name;
            $role->save();

            // Find or create permissions
            $permissionSlugs = collect($request->permissions)->map(function ($permissionName) {
                $permission = Permission::firstOrCreate(
                    ['name' => $permissionName],
                    ['slug' => (string) Str::uuid()]
                );
                return $permission->slug;
            })->all();

            // Sync permissions to role
            $role->permissions()->sync($permissionSlugs);

            DB::commit();

            return response()->json([
                'status'  => 'success',
                'message' => 'Role updated successfully.',
                'data'    => new RoleResource($role->fresh('permissions')),
            ], 200);

        } catch (ModelNotFoundException $e) {
            DB::rollBack();

            return response()->json([
                'status' => 'not_found',
                'message' => 'Role not found.',
            ], 404);
        } catch (Exception $e) {
            DB::rollBack();

            Log::error('Role update error', ['error' => $e->getMessage()]);

            return response()->json([
                'status'  => 'error',
                'message' => 'Failed to update role.',
            ], 500);
        }
    }

    /**
     * Delete a role and detach all its permissions.
     */
    public function delete(Request $request)
    {
        $request->validate([
            'slug' => 'required|string|exists:roles,slug',
        ]);

        DB::beginTransaction();

        try {
            $role = Role::where('slug', $request->slug)->firstOrFail();

            // Detach all permissions related to the role
            $role->permissions()->detach();

            // Delete the role
            $role->delete();

            DB::commit();

            return response()->json([
                'status'  => 'success',
                'message' => 'Role deleted successfully.',
            ], 200);

        } catch (ModelNotFoundException $e) {
            DB::rollBack();

            return response()->json([
                'status' => 'not_found',
                'message' => 'Role not found.',
            ], 404);
        } catch (Exception $e) {
            DB::rollBack();

            Log::error('Role delete error', ['error' => $e->getMessage()]);

            return response()->json([
                'status'  => 'error',
                'message' => 'Failed to delete role.',
            ], 500);
        }
    }
}

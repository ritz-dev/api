<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Http\Resources\RoleResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class RoleController extends Controller
{
    /**
     * Display a listing of roles with optional search, sorting, pagination.
     */
    public function index(Request $request)
    {
        try {
            $validated = $request->validate([
                'search'    => ['nullable', 'array'],
                'orderBy'   => ['nullable', 'in:id,name,slug'],
                'sortedBy'  => ['nullable', 'in:asc,desc'],
                'limit'     => ['nullable', 'integer', 'min:1'],
                'skip'      => ['nullable', 'integer', 'min:0'],
            ]);

            $filter = $validated['search'] ?? [];

            $query = Role::with('permissions')
            ->when(!empty($filter), function ($q) use ($filter) {
                foreach ($filter as $column => $value) {
                    if (!in_array($column, ['name'])) {
                        continue; // skip unsupported fields
                    }

                    // Case-insensitive partial match for name/slug
                    if (in_array($column, ['name', 'slug'])) {
                        $q->whereRaw("LOWER($column) LIKE ?", [strtolower($value) . '%']);
                    } else {
                        $q->where($column, $value);
                    }
                }
            })
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
        try {
            $validated = $request->validate([
                'slug' => ['required', 'string', 'exists:roles,slug'],
            ]);

            $role = Role::with('permissions')
                ->where('slug', $validated['slug'])
                ->first();

            if (!$role) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'Role not found.'
                ], 404);
            }

            return response()->json([
                'status'  => 'success',
                'message' => 'Role retrieved successfully.',
                'data'    => new RoleResource($role),
            ], 200);

        } catch (ValidationException $e) {
            return response()->json([
                'status' => 'validation_error',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Throwable $e) {
            \Log::error('Role retrieval failed', [
                'slug'  => $request->slug,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'status'  => 'error',
                'message' => 'An error occurred while retrieving the role.',
            ], 500);
        }
    }

    /**
     * Store a newly created role with permissions.
     */
    public function store(Request $request)
    {
        try {
            // Validate input
            $validated = $request->validate([
                'name'        => ['required', 'string', 'max:255', 'unique:roles,name'],
                'permissions' => ['required', 'array', 'min:1'],
                'permissions.*' => ['string', 'distinct'],
            ]);

            DB::beginTransaction();

            // Create role with UUID slug
            $role = Role::create([
                'name' => $validated['name'],
            ]);

            // Attach existing permissions by slug or create new ones
            $permissionSlugs = collect($validated['permissions'])->map(function ($permissionName) {
                $permission = Permission::where('name', $permissionName)->first();

                return $permission ? $permission->slug : null;
            })->filter(); 

            $role->permissions()->attach($permissionSlugs);

            DB::commit();

            return response()->json([
                'status'  => 'success',
                'message' => 'Role created successfully.',
                'data'    => new RoleResource($role->fresh('permissions')),
            ], 201);

        } catch (ValidationException $e) {
            return response()->json([
                'status' => 'validation_error',
                'errors' => $e->errors(),
            ], 422);

        } catch (\Throwable $e) {
            DB::rollBack();

            \Log::error('Role creation failed', [
                'error'   => $e->getMessage(),
                'trace'   => $e->getTraceAsString(),
                'request' => $request->all(),
                'user_id' => Auth::id(),
            ]);

            return response()->json([
                'status'  => 'error',
                'message' => 'An error occurred while creating the role.',
            ], 500);
        }
    }

    /**
     * Update an existing role and sync permissions.
     */
    public function update(Request $request)
    {
        try {
            // Validate request
            $validated = $request->validate([
                'slug' => ['required', 'string', 'exists:roles,slug'],
                'name' => ['required', 'string', 'max:255', Rule::unique('roles')->ignore($request->slug, 'slug')],
                'permissions' => ['required', 'array', 'min:1'],
                'permissions.*' => ['string', 'distinct'],
            ]);

            DB::beginTransaction();

            $role = Role::where('slug', $validated['slug'])->firstOrFail();

            // Update name
            $role->update(['name' => $validated['name']]);

            // Create or find permissions and get their slugs
            $permissionSlugs = collect($validated['permissions'])->map(function ($permissionName) {
                return Permission::firstOrCreate(
                    ['name' => $permissionName],
                    ['slug' => (string) Str::uuid()]
                )->slug;
            });

            // Sync permissions
            $role->permissions()->sync($permissionSlugs);

            DB::commit();

            return response()->json([
                'status'  => 'success',
                'message' => 'Role updated successfully.',
                'data'    => new RoleResource($role->fresh('permissions')),
            ], 200);

        } catch (ValidationException $e) {
            return response()->json([
                'status' => 'validation_error',
                'errors' => $e->errors(),
            ], 422);

        } catch (\Throwable $e) {
            DB::rollBack();

            \Log::error('Role update failed', [
                'error'   => $e->getMessage(),
                'trace'   => $e->getTraceAsString(),
                'request' => $request->all(),
                'user_id' => Auth::id(),
            ]);

            return response()->json([
                'status'  => 'error',
                'message' => 'An error occurred while updating the role.',
            ], 500);
        }
    }


    /**
     * Delete a role and detach all its permissions.
     */
   public function delete(Request $request)
    {
        try {
            // Validate request
            $validated = $request->validate([
                'slug' => ['required', 'string', 'exists:roles,slug'],
            ]);

            $currentUser = Auth::user();

            $role = Role::where('slug', $validated['slug'])->first();

            if (!$role) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'Role not found.',
                ], 404);
            }

            // Prevent deletion of Super Admin role
            if ($role->name === 'Super Admin') {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'Cannot delete super admin role',
                ], 403);
            }

            // Detach permissions
            $role->permissions()->detach();

            // Delete role
            $role->delete();

            return response()->json([
                'status'  => 'success',
                'message' => 'Role deleted successfully.',
            ], 200);

        } catch (ValidationException $e) {
            return response()->json([
                'status' => 'validation_error',
                'errors' => $e->errors(),
            ], 422);

        } catch (\Throwable $e) {
            \Log::error('Role deletion failed', [
                'error' => $e->getMessage(),
                'user_id' => Auth::id(),
                'request' => $request->all(),
            ]);

            return response()->json([
                'status'  => 'error',
                'message' => 'An error occurred while deleting the role.',
            ], 500);
        }
    }
}

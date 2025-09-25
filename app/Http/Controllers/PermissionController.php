<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class PermissionController extends Controller
{
    /**
     * List all permissions ordered by id descending.
     */
    public function index(Request $request)
    {
        try {
            $validated = $request->validate([
                'filter'    => ['nullable', 'array'],
                'orderBy'   => ['nullable', 'in:id,name,slug'],
                'sortedBy'  => ['nullable', 'in:asc,desc'],
                'limit'     => ['nullable', 'integer', 'min:1'],
                'skip'      => ['nullable', 'integer', 'min:0'],
            ]);

            $filter = $validated['filter'] ?? [];

            $query = Permission::query()
                ->when(!empty($filter), function ($q) use ($filter) {
                    foreach ($filter as $column => $value) {
                        if (!in_array($column, ['name', 'slug'])) {
                            continue; // skip unsupported fields
                        }

                        // Case-insensitive partial match
                        $q->whereRaw("LOWER($column) LIKE ?", [strtolower($value) . '%']);
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

            $permissions = $query->get();

            return response()->json([
                'status' => 'success',
                'total'  => $total,
                'data'   => $permissions,
            ], 200);

        } catch (ValidationException $e) {
            return response()->json([
                'status' => 'validation_error',
                'errors' => $e->errors(),
            ], 422);
        } catch (Exception $e) {
            Log::error('Permission index error', ['error' => $e->getMessage()]);

            return response()->json([
                'status'  => 'error',
                'message' => 'Failed to retrieve permissions.',
            ], 500);
        }
    }

    public function show(Request $request)
    {
        try {
            // Validate input
            $validated = $request->validate([
                'slug' => ['required', 'string', 'exists:permissions,slug'],
            ]);

            // Fetch permission by slug
            $permission = Permission::where('slug', $validated['slug'])->first();

            // Return formatted response
            return response()->json([
                'status' => 'success',
                'data'   => [
                    'slug'        => $permission->slug,
                    'name'        => $permission->name,
                    'description' => $permission->description,
                ],
            ], 200);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Validation failed.',
                'errors'  => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Failed to retrieve permission.',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }


    /**
     * Only super-admin can create permission.
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        $role = Role::where('name', 'Super Admin')->first();

        if (!$user || $user->role_slug !== $role->slug) {
            return response()->json([
                'status' => 'forbidden',
                'message' => 'Only super-admin can create permissions.'
            ], 403);
        }

        try {
            $validated = $request->validate([
                'name' => 'required|string|unique:permissions,name',
            ]);

            $permission = Permission::create([
                'name' => $validated['name'],
            ]);

            return response()->json([
                'status'  => 'success',
                'message' => 'Permission created successfully.',
                'data'    => $permission,
            ], 201);

        } catch (ValidationException $e) {
            return response()->json([
                'status' => 'validation_error',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            Log::error('Permission store error', ['error' => $e->getMessage()]);

            return response()->json([
                'status'  => 'error',
                'message' => 'An error occurred while creating permission.',
            ], 500);
        }
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $role = Role::where('name', 'Super Admin')->first();

        if (!$user || $user->role_slug !== $role->slug) {
            return response()->json([
                'status' => 'forbidden',
                'message' => 'Only super-admin can update permissions.'
            ], 403);
        }

        try {
            $validated = $request->validate([
                'slug' => 'required|exists:permissions,slug',
                'name' => 'required|string|unique:permissions,name,' . $request->slug . ',slug',
                'description' => 'nullable|string|min:10|max:255'
            ]);

            $permission = Permission::where('slug', $validated['slug'])->firstOrFail();

            $permission->name = $validated['name'];
            $permission->description = $validated['description'] ?? $permission->description;
            $permission->save();

            return response()->json([
                'status'  => 'success',
                'message' => 'Permission updated successfully.',
                'data'    => $permission,
            ], 200);

        } catch (ValidationException $e) {
            return response()->json([
                'status' => 'validation_error',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            Log::error('Permission update error', ['error' => $e->getMessage()]);

            return response()->json([
                'status'  => 'error',
                'message' => 'An error occurred while updating permission.',
            ], 500);
        }
    }


    public function delete(Request $request)
    {
        $user = Auth::user();

        $role = Role::where('name', 'Super Admin')->first();

        if (!$user || $user->role_slug !== $role->slug) {
            return response()->json([
                'status' => 'forbidden',
                'message' => 'Only super-admin can delete permissions.'
            ], 403);
        }

        $protectedSlugs = [
            'system-manage-users',
            'system-manage-roles',
            'system-manage-permissions',
        ];

        try {
            $validated = $request->validate([
                'slug' => 'required|string|exists:permissions,slug',
            ]);

            if (in_array($validated['slug'], $protectedSlugs)) {
                return response()->json([
                    'status' => 'forbidden',
                    'message' => 'This permission is protected and cannot be deleted.'
                ], 403);
            }

            $permission = Permission::where('slug', $validated['slug'])->firstOrFail();
            $permission->delete();

            return response()->json([
                'status'  => 'success',
                'message' => 'Permission deleted successfully.',
            ], 200);

        } catch (ValidationException $e) {
            return response()->json([
                'status' => 'validation_error',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            Log::error('Permission remove error', ['error' => $e->getMessage()]);

            return response()->json([
                'status'  => 'error',
                'message' => 'An error occurred while deleting permission.',
            ], 500);
        }
    }
}
<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    public function index(Request $request)
    {
        try {
            // Validate query params
            $validated = $request->validate([
                'search'   => ['nullable', 'string'],
                'orderBy'  => ['nullable', 'in:id,name,email'],
                'sortedBy' => ['nullable', 'in:asc,desc'],
                'limit'    => ['nullable', 'integer', 'min:1', 'max:100'],
                'skip'     => ['nullable', 'integer', 'min:0'],
            ]);

            // Build query
            $query = User::with('role')
                ->when($validated['search'] ?? null, function ($q, $search) {
                    $q->where('name', 'like', $search . '%')
                    ->orWhere('email', 'like', $search . '%');
                })
                ->when($validated['orderBy'] ?? null, function ($q) use ($validated) {
                    $q->orderBy($validated['orderBy'], $validated['sortedBy'] ?? 'asc');
                }, function ($q) {
                    $q->orderByDesc('id'); // Default sort
                });

            // Clone query for total count
            $total = (clone $query)->count();

            // Apply pagination
            if (isset($validated['skip'])) {
                $query->skip($validated['skip']);
            }

            if (isset($validated['limit'])) {
                $query->take($validated['limit']);
            }

            // Get paginated results
            $users = $query->get();

            return response()->json([
                'status' => 'success',
                'message' => 'Users retrieved successfully.',
                'total'  => $total,
                'data'   => UserResource::collection($users),
            ], 200);
            
        } catch (\Throwable $e) {
            \Log::error('User listing failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'status'  => 'error',
                'message' => 'An error occurred while retrieving users.',
            ], 500);
        }
    }


    public function show(Request $request)
    {

        try {
            $validated = $request->validate([
                'slug' => ['required', 'string', 'exists:users,slug'],
            ]);

            $user = User::with('role')
                ->where('slug', $validated['slug'])
                ->first();

            // If no user found, return a 404
            if (!$user) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'User not found.'
                ], 404);
            }

            return response()->json([
                'status'  => 'success',
                'message' => 'User retrieved successfully.',
                'data'    => new UserResource($user),
            ], 200);

        } catch (ValidationException $e) {
            return response()->json([
                'status' => 'validation_error',
                'errors' => $e->errors(),
            ], 422);
        }  catch (\Throwable $e) {
            \Log::error('User retrieval failed', [
                'slug' => $request->slug,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'status'  => 'error',
                'message' => 'An error occurred while retrieving the user.',
            ], 500);
        }
    }


    public function store(Request $request)
    {
        try {
            // Validate request
            $validated = $request->validate([
                'name'      => ['required', 'string', 'max:255'],
                'email'     => ['required', 'email', 'unique:users,email'],
                'password'  => ['required', 'string', 'min:8', 'confirmed'],
                'role_slug' => ['required', 'string', 'exists:roles,slug'],
                'status'    => ['required', 'string', 'in:active,inactive,pending'],
            ]);

            $currentUser = Auth::user();

            $role = Role::where('name', 'Super Admin')->first();

            // Prevent assigning "super-admin" unless user is one
            if (
                $role->slug === $validated['role_slug'] &&
                $currentUser->role_slug !== $role->slug
            ) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'You are not authorized to assign the super-admin role.',
                ], 403);
            }

            // Create user inside transaction
            DB::beginTransaction();

            $user = User::create([
                'name'      => $validated['name'],
                'email'     => $validated['email'],
                'password'  => Hash::make($validated['password']),
                'role_slug' => $validated['role_slug'],
                'status'    => $validated['status'],
            ]);

            DB::commit();

            return response()->json([
                'status'  => 'success',
                'message' => 'User created successfully.',
                'data'    => new UserResource($user),
            ], 201);

        } catch (ValidationException $e) {
            return response()->json([
                'status' => 'validation_error',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Throwable $e) {
            DB::rollBack();

            \Log::error('User creation failed', [
                'error'   => $e->getMessage(),
                'trace'   => $e->getTraceAsString(),
                'request' => $request->all(),
                'user_id' => Auth::id(),
            ]);

            return response()->json([
                'status'  => 'error',
                'message' => 'An error occurred while creating the user.',
            ], 500);
        }
    }


    public function update(Request $request)
    {
        try {
            // Validate request
            $validated = $request->validate([
                'slug'      => ['required', 'string', 'exists:users,slug'],
                'name'      => ['required', 'string', 'max:255'],
                'email'     => ['required', 'email', 'unique:users,email,' . $request->slug . ',slug'],
                'password'  => ['nullable', 'string', 'min:8', 'confirmed'],
                'role_slug' => ['required', 'string', 'exists:roles,slug'],
                'status'    => ['required', 'string', 'in:active,inactive,pending'],
            ]);

            $currentUser = Auth::user();

            $user = User::where('slug', $validated['slug'])->first();

            if( !$user) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'User not found.',
                ], 404);
            }

            $role = Role::where('name', 'Super Admin')->first();
            // Prevent unauthorized super-admin access
            if (
                $user->role_slug === $role->slug &&
                $currentUser->role_slug !== $role->slug
            ) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'You are not authorized to update a super-admin user.',
                ], 403);
            }

            DB::beginTransaction();

            $updateData = [
                'name'      => $validated['name'],
                'email'     => $validated['email'],
                'status'    => $validated['status'],
                'role_slug' => $validated['role_slug'],
            ];

            if (!empty($validated['password'])) {
                $updateData['password'] = Hash::make($validated['password']);
            }

            $user->update($updateData);

            DB::commit();

            return response()->json([
                'status'  => 'success',
                'message' => 'User updated successfully.',
                'data'    => new UserResource($user),
            ], 200);

        } catch (ValidationException $e) {
            return response()->json([
                'status' => 'validation_error',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Throwable $e) {
            DB::rollBack();

            \Log::error('User update failed', [
                'error'   => $e->getMessage(),
                'trace'   => $e->getTraceAsString(),
                'request' => $request->all(),
                'user_id' => Auth::id(),
            ]);

            return response()->json([
                'status'  => 'error',
                'message' => 'An error occurred while updating the user.',
            ], 500);
        }
    }


    public function delete(Request $request)
    {
        try {
            $request->validate([
                'slug' => ['required', 'exists:users,slug'],
            ]);

            $currentUser = Auth::user();

            $user = User::where('slug', $request->slug)->first();

            if( !$user) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'User not found.',
                ], 404);
            }

            $role = Role::where('name', 'Super Admin')->first();

            if( !$role) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'Role not found.',
                ], 404);
            }

            // Prevent deleting super-admin unless current user is also super-admin
            if ($user->role_slug == $role->slug && $currentUser->role_slug != $role->slug) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'You are not authorized to delete a super-admin user.',
                ], 403);
            }

            $user->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'User deleted successfully.',
            ], 200);

        } catch (ValidationException $e) {
            return response()->json([
                'status' => 'validation_error',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Throwable $e) {
            \Log::error('User deletion failed', ['error' => $e->getMessage()]);
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while deleting the user.',
            ], 500);
        }
    }
}

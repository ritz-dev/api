<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

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
            $request->validate([
                'slug' => 'required|string',
            ]);

            $user = User::with('role')
                ->where('slug', $request->slug)
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

        } catch (\Throwable $e) {
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
            $validated = $request->validate([
                'name'      => 'required|string|max:255',
                'email'     => 'required|email|unique:users,email',
                'password'  => 'required|string|min:8|confirmed',
                'role_slug' => 'required|string|exists:roles,slug',
                'status'    => 'required|string|in:active,inactive,pending', // adjust statuses
            ]);

            $currentUser = Auth::user();

            // Prevent assigning "super-admin" unless current user is "super-admin"
            if ($validated['role_slug'] === 'super-admin' && $currentUser->role_slug !== 'super-admin') {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'You are not allowed to assign the super-admin role.',
                ], 403);
            }

            // Create user
            $user = User::create([
                'slug'      => (string) Str::uuid(),
                'name'      => $validated['name'],
                'email'     => $validated['email'],
                'password'  => Hash::make($validated['password']),
                'role_slug' => $validated['role_slug'],
                'status'    => $validated['status'],
            ]);

            return response()->json([
                'status'  => 'success',
                'message' => 'User created successfully.',
                'data'    => new UserResource($user),
            ], 201); // 201 Created

        } catch (ValidationException $e) {
            return response()->json([
                'status' => 'validation_error',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Throwable $e) {
            \Log::error('User creation failed', ['error' => $e->getMessage()]);
            return response()->json([
                'status'  => 'error',
                'message' => 'An error occurred while creating the user.',
            ], 500);
        }
    }

    public function update(Request $request, string $slug)
    {
        try {
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'email' => [
                    'required',
                    'email',
                    Rule::unique('users')->ignore($slug, 'slug'),
                ],
                'status' => 'required|string|in:active,inactive,pending', // adjust statuses
                'role_slug' => 'required|string|exists:roles,slug',
            ]);

            $currentUser = Auth::user();

            $user = User::where('slug', $slug)->firstOrFail();

            // Prevent non-super-admin from modifying super-admin users
            if ($user->role_slug === 'super-admin' && $currentUser->role_slug !== 'super-admin') {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'You are not authorized to update a super-admin user.',
                ], 403);
            }

            // Prevent assigning super-admin role unless current user is super-admin
            if ($validatedData['role_slug'] === 'super-admin' && $currentUser->role_slug !== 'super-admin') {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'You are not authorized to assign the super-admin role.',
                ], 403);
            }

            $user->update([
                'name'      => $validatedData['name'],
                'email'     => $validatedData['email'],
                'status'    => $validatedData['status'],
                'role_slug' => $validatedData['role_slug'],
            ]);

            return response()->json([
                'status'  => 'success',
                'message' => 'User updated successfully.',
            ], 200);

        } catch (ValidationException $e) {
            return response()->json([
                'status' => 'validation_error',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Throwable $e) {
            \Log::error('User update failed', ['error' => $e->getMessage()]);
            return response()->json([
                'status'  => 'error',
                'message' => 'An error occurred while updating the user.',
            ], 500);
        }
    }

    public function delete(Request $request)
    {
        try {
            $validated = $request->validate([
                'slug' => 'required|string',
            ]);

            $currentUser = $request->user();

            // Find user to delete
            $user = User::where('slug', $validated['slug'])->firstOrFail();

            // Prevent non-super-admin from deleting super-admin users
            if ($user->role_slug === 'super-admin' && $currentUser->role_slug !== 'super-admin') {
                return response()->json([
                    'status' => 'error',
                    'message' => 'You are not authorized to delete a super-admin user.',
                ], 403);
            }

            $user->delete();

            return response()->json([
                'status'  => 'success',
                'message' => 'User deleted successfully.'
            ], 200);

        } catch (ValidationException $e) {
            return response()->json([
                'status' => 'validation_error',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Throwable $e) {
            \Log::error('User deletion failed', ['error' => $e->getMessage()]);
            return response()->json([
                'status'  => 'error',
                'message' => 'An error occurred while deleting the user.',
            ], 500);
        }
    }
}

<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Role;
use App\Models\User;
use App\Models\Permission;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\RolePermission;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        try {
            // 1. Check if the user has permission (e.g., only Super Admin)
            $authUser = auth()->guard('api')->user();

            if (!$authUser || $authUser->role_slug !== 'super-admin') {
                return response()->json([
                    'status' => 'Forbidden. Only super admins can register new admins.'
                ], 403);
            }

            // 2. Validate request
            $validator = Validator::make($request->all(), [
                'name'     => ['required', 'string', 'max:255'],
                'email'    => ['required', 'string', 'email', 'max:255', 'unique:users'],
                'password' => ['required', 'string', 'min:8'],
                'role_slug'=> ['required', Rule::exists('roles', 'slug')],
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            // 3. Create user
            $user = User::create([
                'name'      => $request->name,
                'email'     => $request->email,
                'password'  => Hash::make($request->password),
                'role_slug' => $request->role_slug,
                'slug'      => Str::uuid()->toString(), // or your custom slug logic
            ]);

            return response()->json([
                'message' => 'Admin registered successfully.',
                'user'    => $user
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'Failed to register admin.',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function login(Request $request)
    {
        try {
            $key = 'login:' . $request->ip();

            if (RateLimiter::tooManyAttempts($key, 5)) {
                return response()->json([
                    'message' => 'Too many login attempts. Try again in 1 minute.'
                ], 429);
            }

            $request->validate([
                'email'    => ['required', 'email'],
                'password' => ['required', 'string']
            ]);

            $user = User::where('email', $request->email)->first();

            if (!$user || !Hash::check($request->password, $user->password)) {
                RateLimiter::hit($key, 60); // Block for 60 seconds
                return response()->json([
                    'status' => 'Unauthorized! Invalid email or password.'
                ], 401);
            }

            RateLimiter::clear($key); // Reset throttle

            // Generate access token
            $token = $user->createToken('AdminPanel')->accessToken;

            // Get role and permissions
            $role = Role::where('slug', $user->role_slug)->value('name');

            $permissions = RolePermission::where('role_slug', $user->role_slug)
                ->pluck('permission_slug')
                ->map(function ($slug) {
                    return Permission::where('slug', $slug)->value('name');
                })
                ->filter()
                ->values();

            return response()->json([
                'slug'        => $user->employee_slug,
                'token'       => $token,
                'permissions' => $permissions,
                'role'        => $role,
            ], 200);
        } catch (Exception $e) {
            Log::error('Login error', ['error' => $e->getMessage()]);

            return response()->json([
                'status'  => 'Error',
                'message' => 'Could not process the login. Please try again later.',
            ], 500);
        }
    }

    public function logout()
    {
        try {
            $user = auth()->guard('api')->user();

            if (!$user || !$user->token()) {
                return response()->json([
                    'status' => 'Unauthorized! No active session found.'
                ], 401);
            }

            $user->token()->delete(); // Only logs out current device

            return response()->json([
                'message' => 'Logged out successfully.',
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'Logout failed.',
                'message' => $e->getMessage()
            ], 500);
        }
    }


    public function me(Request $request)
    {
        try {
            $user = auth()->guard('api')->user();

            if (!$user) {
                return response()->json([
                    'status' => 'Unauthorized! No active session found.'
                ], 401);
            }

            $role = Role::where('slug', $user->role_slug)->value('name');

            $permissionSlugs = RolePermission::where('role_slug', $user->role_slug)
                ->pluck('permission_slug');

            $permissions = Permission::whereIn('slug', $permissionSlugs)
                ->pluck('name')
                ->toArray();

            return response()->json([
                'slug' => $user->slug,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $role,
                'permissions' => $permissions,
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'Could not retrieve user.',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function reset(Request $request)
    {
        $request->validate([
            'token'    => 'required',
            'email'    => 'required|email',
            'password' => 'required|confirmed|min:8',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill(['password' => Hash::make($password)])->save();
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return response()->json(['message' => 'Password reset successfully']);
        }

        throw ValidationException::withMessages(['email' => [__($status)]]);
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password'     => 'required|min:8|confirmed',
        ]);

        $user = $request->user();

        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json(['message' => 'Current password is incorrect'], 422);
        }

        $user->update(['password' => Hash::make($request->new_password)]);

        return response()->json(['message' => 'Password changed successfully']);
    }

    public function sendResetLinkEmail(Request $request)
    {
        $request->validate(['email' => 'required|email|exists:users,email']);

        $status = Password::sendResetLink($request->only('email'));

        if ($status === Password::RESET_LINK_SENT) {
            return response()->json(['message' => 'Reset link sent.']);
        }

        return response()->json(['message' => 'Unable to send reset link.'], 500);
    }


    public function index(Request $request){
        try {
            
            $validated = $request->validate([
                'search'    => ['nullable', 'string'],
                'orderBy'   => ['nullable', 'in:id,name,email'], // ← adjust allowed columns
                'sortedBy'  => ['nullable', 'in:asc,desc'],
                'limit'     => ['nullable', 'integer', 'min:1'],
                'skip'      => ['nullable', 'integer', 'min:0'],
            ]);

            $query = User::with('role')
                ->when(!empty($validated['search']), fn($q) =>
                    $q->where('name', 'like', $validated['search'].'%'))
                ->when(!empty($validated['orderBy']), function ($q) use ($validated) {
                    $q->orderBy($validated['orderBy'], $validated['sortedBy'] ?? 'asc');
                }, fn($q) => $q->orderByDesc('id')); // default order

            $total = (clone $query)->count();

            if (!empty($validated['skip']))  $query->skip($validated['skip']);
            if (!empty($validated['limit'])) $query->take($validated['limit']);

            $users = $query->get();
            $data  = UserResource::collection($users);

            return response()->json([
                'status' => 'OK! The request was successful.',
                'total'  => $total,
                'data'   => $data,
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'error'   => 'Bad Request! The request is invalid.',
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    public function show(Request $request){

        $request->validate([
            'slug' => 'required|string',
        ]);

        try{
            $query = User::with(['role'])->where('slug',$request->slug)->orderBy('id','desc')->first();

            $data =new UserResource($query);

            return response()->json($data,200);
        }catch(Exception $e){
            return response()->json([
                'error' => 'Bad Request!. The request is invalid.',
                'message' => $e->getMessage()
            ],400);
        }
    }

    public function create (Request $request){
        try{
            $request->validate([
                'name' => 'required|string',
                'email' => 'required|email|unique:users,email',
                'status' => 'required|string',
            ]);

            $user = new User;
            $user->slug = Str::uuid();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = Hash::make($request->password);
            $user->role_slug = $request->role_slug;
            $user->status = $request->status;
            $user->save();

            return response()->json([
                'status' => "Created successful.",
            ],200);

        }catch (ValidationException $e) {
            return response()->json([
                'status' => 'Validation error.',
                'errors' => $e->errors(),
            ], 422);
        } catch (Throwable $e) {
            return response()->json([
                'status' => 'An error occurred while adding.',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function update(Request $request) {
        try {
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'email' => [
                    'required',
                    'email',
                    Rule::unique('users')->ignore($request->slug, 'slug'),
                ],
                'status' => 'required|string',
            ]);
    
            $user = User::where('slug', $request->slug)->firstOrFail();

            $user->update([
                'name' => $validatedData['name'],
                'email' => $validatedData['email'],
                'status' => $validatedData['status'],
                'role_id' => 1,
            ]);
    
            return response()->json([
                'status' => "Updated successfully.",
            ], 200);
    
        } catch (ValidationException $e) {
            return response()->json([
                'status' => 'Validation error.',
                'errors' => $e->errors(),
            ], 422);
        } catch (Throwable $e) {
            return response()->json([
                'status' => 'An error occurred while updating.',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function password(Request $request) {
        try {
            $validatedData = $request->validate([
                
                'password' => 'required|string|min:6',
            ]);
    
            $user = User::where('slug', $request->slug)->firstOrFail();

            $user->update([
                'password' => Hash::make($validatedData['password']),
            ]);
    
            return response()->json([
                'status' => "Updated successfully.",
            ], 200);
    
        } catch (ValidationException $e) {
            return response()->json([
                'status' => 'Validation error.',
                'errors' => $e->errors(),
            ], 422);
        } catch (Throwable $e) {
            return response()->json([
                'status' => 'An error occurred while updating.',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function delete(Request $request) {
        $validated = $request->validate([
            'slug' => 'required|string', // Ensure the ID exists in the database
        ]);

        try {
            $user = User::where('slug', $request->slug)->firstOrFail();
            $user->delete();
            return response()->json(['message' => 'User deleted successfully'], 200);

        } catch (ValidationException $e) {
            return response()->json([
                'status' => 'Validation error.',
                'errors' => $e->errors(),
            ], 422);
        } catch (Throwable $e) {
            return response()->json([
                'status' => 'An error occurred while updating.',
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}

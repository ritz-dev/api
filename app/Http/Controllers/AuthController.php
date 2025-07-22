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
use Illuminate\Support\Facades\Auth;
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
            // Rate limiter key based on client IP
            $key = 'login:' . $request->ip();

            // Limit to 5 attempts per minute
            if (RateLimiter::tooManyAttempts($key, 5)) {
                return response()->json([
                    'message' => 'Too many login attempts. Try again in 1 minute.'
                ], 429);
            }

            // Validate input
            $request->validate([
                'email'    => ['required', 'email'],
                'password' => ['required', 'string']
            ]);

            // Check user existence
            $user = User::where('email', $request->email)->first();

            if (!$user || !Hash::check($request->password, $user->password)) {
                RateLimiter::hit($key, 60); // Throttle for 60 seconds
                return response()->json([
                    'status'  => 'error',
                    'message' => 'Invalid email or password.'
                ], 401);
            }

            // Clear rate limit
            RateLimiter::clear($key);

            // Create access token (OAuth2 via Passport)
            $token = $user->createToken('AdminPanel')->accessToken;

            // Get role name from role_slug
            $role = Role::where('slug', $user->role_slug)->value('name');

            // Get permission names from role-permissions
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
            
        } catch (\Throwable $e) {
            // Log exception details
            Log::error('Login error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'status'  => 'error',
                'message' => 'An error occurred during login. Please try again later.'
            ], 500);
        }
    }

    public function logout()
    {
        try {
            // Get the authenticated user via the API guard
            $user = auth()->guard('api')->user();

            // Check for valid user session
            if (!$user || !$user->token()) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'Unauthorized. No active session found.'
                ], 401);
            }

            // Revoke the token (only logs out current device/session)
            $user->token()->revoke();

            return response()->json([
                'status'  => 'success',
                'message' => 'Logged out successfully.'
            ], 200);

        } catch (\Throwable $e) {
            // Log error for debugging and alerting
            \Log::error('Logout error', ['error' => $e->getMessage()]);

            return response()->json([
                'status'  => 'error',
                'message' => 'Logout failed. Please try again.'
            ], 500);
        }
    }


    public function me(Request $request)
    {
        try {
            // Get the authenticated user from the API guard
            $user = auth()->guard('api')->user();

            if (!$user) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'Unauthorized. No active session found.'
                ], 401);
            }

            // Fetch role name from role_slug
            $role = Role::where('slug', $user->role_slug)->value('name');

            // Fetch permission names via optimized query
            $permissions = Permission::whereIn('slug', function ($query) use ($user) {
                    $query->select('permission_slug')
                        ->from('role_permissions')
                        ->where('role_slug', $user->role_slug);
                })
                ->pluck('name')
                ->toArray();

            return response()->json([
                'status'      => 'success',
                'data'        => [
                    'slug'        => $user->slug,
                    'name'        => $user->name,
                    'email'       => $user->email,
                    'role'        => $role,
                    'permissions' => $permissions,
                ]
            ], 200);

        } catch (\Throwable $e) {
            \Log::error('User profile retrieval failed', [
                'error' => $e->getMessage(),
                'user_id' => auth()->id(),
            ]);

            return response()->json([
                'status'  => 'error',
                'message' => 'Could not retrieve user. Please try again later.'
            ], 500);
        }
    }

    public function reset(Request $request)
    {
        try {
            $request->validate([
                'token'    => 'required|string',
                'email'    => 'required|email',
                'password' => 'required|confirmed|min:8',
            ]);

            $status = Password::reset(
                $request->only('email', 'password', 'password_confirmation', 'token'),
                function ($user, $password) {
                    $user->forceFill([
                        'password' => Hash::make($password)
                    ])->save();

                    // Invalidate all tokens (log out all sessions)
                    $user->tokens()->delete();

                    event(new \Illuminate\Auth\Events\PasswordReset($user));
                }
            );

            if ($status === Password::PASSWORD_RESET) {
                return response()->json([
                    'status'  => 'success',
                    'message' => 'Password has been reset successfully.'
                ], 200);
            }

            throw ValidationException::withMessages([
                'email' => [__($status)],
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'status' => 'validation_error',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Throwable $e) {
            Log::error('Password reset error', ['error' => $e->getMessage()]);
            return response()->json([
                'status'  => 'error',
                'message' => 'Failed to reset password. Please try again later.',
            ], 500);
        }
    }

    public function changePassword(Request $request)
    {
        try {
            $request->validate([
                'current_password' => 'required|string',
                'new_password'     => 'required|string|min:8|confirmed|different:current_password',
            ]);

            $user = $request->user();

            if (!Hash::check($request->current_password, $user->password)) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'Current password is incorrect.'
                ], 422);
            }

            $user->forceFill([
                'password' => Hash::make($request->new_password)
            ])->save();

            $user->tokens()->delete();

            event(new \Illuminate\Auth\Events\PasswordReset($user));

            return response()->json([
                'status'  => 'success',
                'message' => 'Password changed successfully.'
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'status' => 'validation_error',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Throwable $e) {
            Log::error('Change password error', ['error' => $e->getMessage()]);
            return response()->json([
                'status'  => 'error',
                'message' => 'Failed to change password. Please try again later.',
            ], 500);
        }
    }

    public function sendResetLinkEmail(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|email|exists:users,email'
            ]);

            $status = Password::sendResetLink(
                $request->only('email')
            );

            if ($status === Password::RESET_LINK_SENT) {
                return response()->json([
                    'status'  => 'success',
                    'message' => 'A password reset link has been sent to your email address.'
                ], 200);
            }

            return response()->json([
                'status'  => 'error',
                'message' => 'Unable to send reset link. Please try again later.'
            ], 500);
        } catch (ValidationException $e) {
            return response()->json([
                'status' => 'validation_error',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Throwable $e) {
            Log::error('Send reset link email error', ['error' => $e->getMessage()]);
            return response()->json([
                'status'  => 'error',
                'message' => 'Failed to send reset link. Please try again later.',
            ], 500);
        }
    }
}

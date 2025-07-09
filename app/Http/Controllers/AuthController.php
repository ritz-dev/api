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
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function register(Request $request){
        try{
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|string|min:6',
                'role_slug' => 'required|exists:roles,slug',
                'role_name' => 'required|string|max:100',
                'employee_slug' => 'required|string|max:100',
            ]);
            // Check if the role exists
            $role = Role::where('slug', $validated['role_slug'])->first();
            
            if (!$role) {
                return response()->json([
                    'message' => 'Role not found.'
                ], 404);
            }

            // Check if the employee already exists
            $baseUrl = config('services.user.url');

            $endpoint = match ($validated['role_name']) {
                'Teacher' => "$baseUrl" . "/teachers/show",
                default => null,
            };

            if ($endpoint) {
                $studentResponse = Http::withHeaders([
                    'Accept' => 'application/json',
                    // You can include auth header if needed
                    // 'Authorization' => $request->header('Authorization'),
                ])->post($endpoint, [
                    'slug' => $validated['employee_slug']
                ]);
            }

            if ($studentResponse->status() !== 200 || !$studentResponse->json('data')) {
                return response()->json([
                    'message' => 'Student not found in the system.'
                ], 404);
            }

            DB::beginTransaction();

            // Create the user
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role_slug' => $role->slug,
                'employee_slug' => $validated['employee_slug'],
            ]);

            $token = $user->createToken('SMS')->accessToken;

            $role = Role::where('slug',$user->role_slug)->pluck('name')->first();
            $role_permissions = RolePermission::where('role_slug',$user->role_slug)->get();

            $permission = [];

            foreach($role_permissions as $role_permission){
                $permission [] = Permission::where('slug',$role_permission->permission_sluf)->pluck('name')->first();
            }

            DB::commit();

            return response()->json([
                'slug' => $user->employee_id,
                'token' => $token,
                'permissions' => $permission,
                'role' => $role,
            ],200);

        }catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'Bad Request! Could not process the registration.',
                'message' => $e->getMessage()
            ], 400);
        }
    }

    public function login(Request $request)
    {
        try {
            $credentials = [
                'email'    => $request->email,
                'password' => $request->password
            ];

            $user = User::where('email', $request->email)->first();

            if ($user && Hash::check($request->password, $user->password)) {
                $token = $user->createToken('SMS')->accessToken;

                $role = Role::where('slug',$user->role_slug)->pluck('name')->first();
                $role_permissions = RolePermission::where('role_slug',$user->role_slug)->get();

                $permission = [];

                foreach($role_permissions as $role_permission){
                    $permission [] = Permission::where('slug',$role_permission->permission_slug)->pluck('name')->first();
                }
                return response()->json([
                    'slug' => $user->employee_slug,
                    'token' => $token,
                    'permissions' => $permission,
                    'role' => $role,
                ],200);
            }else{
                return response()->json([
                    'status' => 'Unauthorized! Invalid email or password.'
                ], 401);
            }
        } catch (Exception $e) {
            return response()->json([
                'status' => 'Bad Request! Could not process the login.',
                'message' => $e->getMessage()
            ], 400);
        }
    }

    public function logout(){
        $user = auth()->guard('api')->user();

        if ($user) {
            $user->tokens->each(function ($token) {
                $token->delete();
            });

            return response()->json([
                'message' => "Logout Successfully",
            ],200);
        }else{
            return response()->json([
                'status' => 'Unauthorized! No active session found.'
            ], 401);
        }
    }

    public function me (Request $request) {

        $user = auth()->guard('api')->user();

        $name = $user->name;

        $role = Role::where('slug',$user->role_slug)->pluck('name')->first();

        $role_permissions = RolePermission::where('role_slug',$user->role_slug)->get();

        $permissionIds = [];

        foreach($role_permissions as $role_permission){
            $permissionIds[] = $role_permission->permission_slug;
        }

        $permission = [];

        foreach($permissionIds as $permissionId){
            $permission[] = Permission::where('slug',$permissionId)->pluck('name')->first();
        }

        $data = [
            'slug' => $user->slug,
            'name' => $name,
            'email' => $user->email,
            'role' => $role,
            // 'permissions' => $permission,
        ];

        if(!$data) {
            return response()->json([
                'status' => 'Unauthorized! No active session found.'
            ], 401);
        }

        return response()->json($data,200);
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

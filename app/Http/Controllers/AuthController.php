<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Role;
use App\Models\User;
use App\Models\Permission;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\RolePermission;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Rule;

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

    public function list(Request $request){
        try{
            $limit = (int)$request->limit;
            $search = $request->search;

            $query = User::with(['role'])->orderBy('id','desc');

            if($search){
                $query->where('name','LIKE',$search.'%');
            }

            $query_data = $limit ? $query->paginate($limit) : $query->get();

            $data = UserResource::collection($query_data);

            $total = Role::count();

            return response()->json([
                'status' => "OK! The request was successful.",
                'total' => $total,
                'data' => $data
            ],200);
        }catch(Exception $e){
            return response()->json([
                'error' => 'Bad Request!. The request is invalid.',
                'message' => $e->getMessage()
            ],400);
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
            $user->password = Hash::make('superpassword');
            $user->role_id = 1;
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

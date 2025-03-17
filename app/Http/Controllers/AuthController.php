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
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Rule;

class AuthController extends Controller
{
    public function register(Request $request){

        $registeredData = $request->validate([
            'name' => 'required|string',
            'email' => 'email|required|string|unique:users',
            'password' => 'required|confirmed'
        ]);

        try{
            DB::beginTransaction();
            $user = new User;
            $user->slug = Str::uuid();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = Hash::make($request->password);
            $user->role_id = $request->role_id;
            $user->save();

            $token = $user->createToken('SMS')->accessToken;

            $role = Role::where('id',$user->role_id)->pluck('name')->first();
            $role_permissions = RolePermission::where('role_id',$user->role_id)->get();

            $permission = [];

            foreach($role_permissions as $role_permission){
                $permission [] = Permission::where('id',$role_permission->permission_id)->pluck('name')->first();
            }

            DB::commit();

            return response()->json([
                "token" => $token,
                'permissions' => $permission,
                'role' => $role
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

            $role = Role::where('id',$user->role_id)->pluck('name')->first();
            $role_permissions = RolePermission::where('role_id',$user->role_id)->get();

            $permission = [];

            foreach($role_permissions as $role_permission){
                $permission [] = Permission::where('id',$role_permission->permission_id)->pluck('name')->first();
            }
            return response()->json([
                'token' => $token,
                'permissions' => $permission,
                'role' => $role
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

        $role = Role::where('id',$user->role_id)->pluck('name')->first();

        $role_permissions = RolePermission::where('role_id',$user->role_id)->get();

        $permissionIds = [];

        foreach($role_permissions as $role_permission){
            $permissionIds[] = $role_permission->permission_id;
        }

        $permission = [];

        foreach($permissionIds as $permissionId){
            $permission[] = Permission::where('id',$permissionId)->pluck('name')->first();
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

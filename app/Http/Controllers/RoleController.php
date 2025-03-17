<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\RolePermission;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\RoleResource;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class RoleController extends Controller
{
    public function list(Request $request){
        try{
            $limit = (int)$request->limit;
            $search = $request->search;

            $query = Role::with('permissions')
                            ->orderBy('id','desc');
            if($search){
                $query->where('name','LIKE',$search.'%');
            }
            $query_data = $limit ? $query->paginate($limit) : $query->get();

            $data = RoleResource::collection($query_data);

            $total = Role::count();

            return response()->json([
                'status' => "OK! The request was successful.",
                'data' => $data,
                'total' => $total
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
            $query = Role::with(['permissions'])->where('slug',$request->slug)->orderBy('id','desc')->first();

            $data = new RoleResource($query);

            return response()->json($data,200);
        } catch(Exception $e){
            return response()->json([
                'error' => 'Bad Request!. The request is invalid.',
                'message' => $e->getMessage()
            ],400);
        }
    }

    public function create(Request $request){
        try{
            DB::beginTransaction();

            $request->validate([
                'name' => 'required|string',
                'permissions' => 'required|array'
            ]);

            $role = new Role;
            $role->slug = Str::uuid();
            $role->name = $request->name;
            $role->description = $request->description;
            $role->save();

            $permissionIds = collect($request->permissions)->map(function ($permissionName) use ($role) {

                $permission = Permission::where('name', $permissionName)->first();
                if(!$permission){
                    $permission = new Permission;
                    $permission->name = $permissionName;
                    $permission->save();
                    return $permission->id;
                }else{
                    return $permission->id;
                }
            })->filter()->all();

            $role->permissions()->attach($permissionIds);

            DB::commit();

            return response()->json([
                'status' => "OK! The request was successful.",
            ],200);

        }catch(Exception $e){
            DB::rollBack();
            return response()->json([
                'status' => 'An error occurred while adding.',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request){
        try {
            DB::beginTransaction();

            $request->validate([
                "name" => "required|string",
                "permissions" => "required|array"
            ]);

            $role = Role::with('permissions')->where('slug',$request->slug)->firstOrFail();
            $role->name = $request->name;
            $role->description = $request->description;
            $role->save();

            $permissionsArray = $request->permissions;

            $permissionIds = collect($permissionsArray)->map(function ($permissionName) {

                $permission = Permission::where('name', $permissionName)->first();
                if(!$permission){
                    $permission = new Permission;
                    $permission->name = $permissionName;
                    $permission->save();
                    return $permission->id;
                }else{
                    return $permission->id;
                }
            })->filter()->all();

            $role->permissions()->sync($permissionIds);

            $transformedRole = [
                'id' => $role->id,
                'name' => $role->name,
                'description' => $role->description,
                'permissions' => $role->permissions->pluck('name')->toArray(),
            ];

            DB::commit();

            return response()->json(["status" => "OK! The request was successful."],200);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'status' => 'An error occurred while updating the role.',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function delete(Request $request){
        $validated = $request->validate([
            'slug' => 'required|string', // Ensure the ID exists in the database
        ]);

        try {
            DB::beginTransaction();

            $role = Role::where('slug',$request->slug)->firstOrFail();

            $role->permissions()->detach();

            $role->delete();

            DB::commit();

            return response()->json([
                'status' => 'OK! The request was successful.',
            ], 200);

        }catch (ModelNotFoundException $e) {
            DB::rollBack();

            return response()->json([
                'status' => 'Role does not exist.',
                'message' => $e->getMessage(),
            ], 404);
        }catch (Exception $e) {
            DB::rollBack();

            return response()->json([
                'status' => 'An error occurred while deleting the role.',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

}

<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Resources\RoleResource;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class RoleController extends Controller
{
    public function index(Request $request)
    {
        try {

            $validated = $request->validate([
                'search'    => ['nullable', 'string'],
                'orderBy'   => ['nullable', 'in:id,name,slug'],
                'sortedBy'  => ['nullable', 'in:asc,desc'],
                'limit'     => ['nullable', 'integer', 'min:1'],
                'skip'      => ['nullable', 'integer', 'min:0'],
            ]);

            $query = Role::with('permissions')
                ->when(!empty($validated['search']),  fn ($q) => $q->where('name', 'like', $validated['search'].'%'))
                ->when(!empty($validated['orderBy']), function ($q) use ($validated) {
                    // use provided column & direction
                    $q->orderBy($validated['orderBy'], $validated['sortedBy'] ?? 'asc');
                }, fn ($q) => $q->orderByDesc('id'));
           
            $total = (clone $query)->count();

            if (!empty($validated['skip']))   { $query->skip($validated['skip'] * $validated['limit']); }
            if (!empty($validated['limit']))  { $query->take($validated['limit']); }

            $roles = $query->get();
            $data  = RoleResource::collection($roles);

            logger($roles);

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
            
            $request->validate([
                'name' => ['required', 'string'],
                'permissions' => ['required', 'array']
            ]);

            DB::beginTransaction();

            $role = new Role;
            $role->slug = Str::uuid();
            $role->name = $request->name;
            $role->save();

            $permissionIds = collect($request->permissions)->map(function ($permissionName) use ($role) {

                $permission = Permission::where('name', $permissionName)->first();

                if(!$permission){
                    $permission = new Permission;
                    $permission->name = $permissionName;
                    $permission->save();
                    return $permission->slug;
                }else{
                    return $permission->slug;
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
            

            $request->validate([
                'slug' => ['nullable', 'string', 'exists:roles,slug'],
                'name' => ['required', 'string'],
                'permissions' => ['required', 'array']
            ]);

            DB::beginTransaction();

            $role = Role::with('permissions')->where('slug',$request->slug)->firstOrFail();
            $role->name = $request->name;
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

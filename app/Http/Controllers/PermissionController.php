<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Permission;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    public function list(Request $request){
        try {

            $query = Permission::select('slug', 'name')->orderBy('id', 'desc');

            $data = $query->get();

            // Map the data to encrypt slug and name
            $data->getCollection()->transform(function ($item) {
                return [
                    'slug' => $this->encryptFixed36($item->slug),
                    'name' => $this->encryptFixed36($item->name),
                    'description' => $this->encryptFixed36($item->description)
                ];
            });

            $total = Permission::count();

            return response()->json([
                'status' => "OK! The request was successful.",
                'data' => $data,
                'total' => $total
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'Bad Request! The request is invalid.',
                'message' => $e->getMessage()
            ], 400);
        }
    }

    public function create(Request $request){
        try{
            $request->validate([
                "name" => "required|unique:permissions,name",
            ]);

            $permission = new Permission;
            $permission->slug = Str::uuid();
            $permission->name = $request->name;
            $permission->save();

            return response()->json([
                'status' => 'OK! The request was successful.'
            ]);
        }catch(Exception $e){
            return response()->json([
                'status' => 'An error occurred while adding.',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request){
        try{
            $request->validate([
                "name" => "required|unique:permissions,name,".$request->id,
            ]);

            $permission = Permission::findOrFail($request->id);
            $permission->name = $request->name;
            $permission->save();

            return response()->json($permission,200);
        }catch(Exception $e){
            return response()->json([
                'status' => 'An error occurred while editing.',
                'message' => $e->getMessage()
            ], 500);
        }

    }

    public function remove(Request $request){
        try{
            Permission::where('slug',$request->slug)->delete();

            return response()->json(['status' => 'Permission deleted successfully'],200);
        }catch(Exception $e){
            return response()->json([
                'status' => 'An error occurred while deleting.',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}

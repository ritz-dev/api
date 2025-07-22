<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Permission;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class PermissionController extends Controller
{
    /**
     * List all permissions ordered by id descending.
     */
    public function index(Request $request)
    {
        try {
            $query = Permission::orderBy('id', 'desc');

            $data = $query->get();

            $total = Permission::count();

            return response()->json([
                'status' => "success",
                'data' => $data,
                'total' => $total
            ], 200);
        } catch (Exception $e) {
            Log::error('Permission index error', ['error' => $e->getMessage()]);

            return response()->json([
                'status' => 'error',
                'message' => 'Failed to fetch permissions.'
            ], 500);
        }
    }

    /**
     * Only super-admin can create permission.
     */
    public function create(Request $request)
    {
        $user = Auth::user();

        if (!$user || $user->role_slug !== 'super-admin') {
            return response()->json([
                'status' => 'forbidden',
                'message' => 'Only super-admin can create permissions.'
            ], 403);
        }

        try {
            $request->validate([
                'name' => 'required|string|unique:permissions,name',
            ]);

            $permission = new Permission();
            $permission->slug = (string) Str::uuid();
            $permission->name = $request->name;
            $permission->save();

            return response()->json([
                'status' => 'success',
                'message' => 'Permission created successfully.',
                'data' => $permission,
            ], 201);

        } catch (Exception $e) {
            Log::error('Permission create error', ['error' => $e->getMessage()]);

            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while adding permission.',
            ], 500);
        }
    }

    /**
     * Only super-admin can update permission.
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        if (!$user || $user->role_slug !== 'super-admin') {
            return response()->json([
                'status' => 'forbidden',
                'message' => 'Only super-admin can update permissions.'
            ], 403);
        }

        try {
            $request->validate([
                'id' => 'required|exists:permissions,id',
                'name' => 'required|string|unique:permissions,name,' . $request->id,
            ]);

            $permission = Permission::findOrFail($request->id);
            $permission->name = $request->name;
            $permission->save();

            return response()->json([
                'status' => 'success',
                'message' => 'Permission updated successfully.',
                'data' => $permission,
            ], 200);

        } catch (Exception $e) {
            Log::error('Permission update error', ['error' => $e->getMessage()]);

            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while editing permission.',
            ], 500);
        }
    }

    /**
     * Only super-admin can delete permission.
     */
    public function remove(Request $request)
    {
        $user = Auth::user();

        if (!$user || $user->role_slug !== 'super-admin') {
            return response()->json([
                'status' => 'forbidden',
                'message' => 'Only super-admin can delete permissions.'
            ], 403);
        }

        try {
            $request->validate([
                'slug' => 'required|string|exists:permissions,slug',
            ]);

            Permission::where('slug', $request->slug)->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Permission deleted successfully',
            ], 200);

        } catch (Exception $e) {
            Log::error('Permission remove error', ['error' => $e->getMessage()]);

            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while deleting permission.',
            ], 500);
        }
    }
}

<?php

use App\Http\Controllers\ApiGatewayController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\APIs\StudentController;
use App\Http\Controllers\APIs\TeacherController;
use App\Http\Controllers\APIs\EmployeeController;
use App\Http\Controllers\APIs\PersonalController;

use App\Models\User;


Route::get('/', function () {
    return view('welcome');
});

Route::post('/login',[AuthController::class,'login']);

Route::post('/register',[AuthController::class,'register']);

Route::prefix('finance')->group(function () {
    Route::any('{endpoint}', [ApiGatewayController::class, 'handleFinanceService'])->where('endpoint', '.*');
});

Route::middleware('auth:api')->group(function(){

    Route::prefix('user-management')->group(function () {
        Route::any('{endpoint}', [ApiGatewayController::class, 'handleUserManagementService'])->where('endpoint', '.*');
    });
   
    Route::prefix('academic')->group(function () {
        Route::any('{endpoint}', [ApiGatewayController::class, 'handleAcademicService'])->where('endpoint', '.*');
    });

    Route::prefix('finance')->group(function () {
        Route::any('{endpoint}', [ApiGatewayController::class, 'handleFinanceService'])->where('endpoint', '.*');
    });

    Route::post('/logout',[AuthController::class,'logout']);
    Route::get('/me',[AuthController::class,'me']);

    // user
    Route::prefix('users')->group(function(){
        Route::post('list',[AuthController::class,'list']);
        Route::post('create',[AuthController::class,'create']);
        Route::post('detail',[AuthController::class,'show']);
        Route::put('update',[AuthController::class,'update']);
        Route::post('delete',[AuthController::class,'delete']);
        Route::post('change-password',[AuthController::class,'password']);
    });

    
    // Permission
    Route::prefix('permissions')->group(function(){
        Route::post('list',[PermissionController::class,'list']);
        Route::post('create',[PermissionController::class,'create']);
        Route::post('detail',[PermissionController::class,'show']);
        Route::put('update',[PermissionController::class,'update']);
        Route::post('delete',[PermissionController::class,'delete']);
    });

    //  Role
    Route::prefix('roles')->group(function(){
        Route::post('list',[RoleController::class,'list']);
        Route::post('create',[RoleController::class,'create']);
        Route::post('detail',[RoleController::class,'show']);
        Route::put('update',[RoleController::class,'update']);
        Route::post('delete',[RoleController::class,'delete']);
    });

});

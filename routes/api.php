<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\ApiGatewayController;
use App\Http\Controllers\PermissionController;


Route::get('/', function () {
    return view('welcome');
});

Route::post('/login',[AuthController::class,'login']);

Route::post('/register',[AuthController::class,'register']);

Route::prefix('finance')->group(function () {
    Route::any('{endpoint}', [ApiGatewayController::class, 'handleFinanceService'])->where('endpoint', '.*');
});

Route::middleware('auth:api')->group(function(){

    Route::prefix('user')->group(function () {
        Route::any('{endpoint}', [ApiGatewayController::class, 'handleUserService'])->where('endpoint', '.*');
    });
   
    Route::prefix('academic')->group(function () {
        Route::any('{endpoint}', [ApiGatewayController::class, 'handleAcademicService'])->where('endpoint', '.*');
    });

    Route::prefix('finance')->group(function () {
        Route::any('{endpoint}', [ApiGatewayController::class, 'handleFinanceService'])->where('endpoint', '.*');
    });

    Route::middleware('role:teacher')->prefix('teacher')->group(function () {
        Route::any('{endpoint}', [ApiGatewayController::class, 'handleTeacherService'])->where('endpoint', '.*');
    });

    Route::middleware('role:student')->prefix('student')->group(function () {
        Route::any('{endpoint}', [ApiGatewayController::class, 'handleStudentService'])->where('endpoint', '.*');
    });
    
    Route::middleware('role:hr')->prefix('hr')->group(function () {
        Route::any('{endpoint}', [ApiGatewayController::class, 'handleHRService'])->where('endpoint', '.*');
    });

    Route::post('/logout',[AuthController::class,'logout']);
    Route::get('/me',[AuthController::class,'me']);
    Route::post('/me',[AuthController::class,'me']);

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

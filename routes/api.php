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

    Route::middleware('role:Teacher')->prefix('teacher')->group(function () {
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
        Route::post('/',[AuthController::class,'index']);
        Route::post('store',[AuthController::class,'store']);
        Route::post('show',[AuthController::class,'show']);
        Route::post('update',[AuthController::class,'update']);
        Route::post('delete',[AuthController::class,'delete']);
        Route::post('change-password',[AuthController::class,'password']);
    });

    
    // Permission
    Route::prefix('permissions')->group(function(){
        Route::post('/',[PermissionController::class,'index']);
        Route::post('store',[PermissionController::class,'store']);
        Route::post('show',[PermissionController::class,'show']);
        Route::post('update',[PermissionController::class,'update']);
        Route::post('delete',[PermissionController::class,'delete']);
    });

    //  Role
    Route::prefix('roles')->group(function(){
        Route::post('/',[RoleController::class,'index']);
        Route::post('store',[RoleController::class,'store']);
        Route::post('show',[RoleController::class,'show']);
        Route::post('update',[RoleController::class,'update']);
        Route::post('delete',[RoleController::class,'delete']);
    });

});

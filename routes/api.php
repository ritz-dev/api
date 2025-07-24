<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ApiGatewayController;
use App\Http\Controllers\PermissionController;

Route::get('/', function () {
    return view('welcome');
});

// Public routes
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/forgot-password', [AuthController::class, 'sendResetLinkEmail']);
Route::post('/reset-password', [AuthController::class, 'reset']);

// Public finance route (no auth)
Route::prefix('finance')->group(function () {
    Route::any('{endpoint}', [ApiGatewayController::class, 'handleFinanceService'])
        ->where('endpoint', '.*');
});

Route::middleware('auth:api')->group(function () {

    Route::post('/change-password', [AuthController::class, 'changePassword']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/me', [AuthController::class, 'me']);

    // User service gateway
    Route::prefix('user')->group(function () {
        Route::any('{endpoint}', [ApiGatewayController::class, 'handleUserService'])
            ->where('endpoint', '.*');
    });

    // Academic service gateway
    Route::prefix('academic')->group(function () {
        Route::any('{endpoint}', [ApiGatewayController::class, 'handleAcademicService'])
            ->where('endpoint', '.*');
    });

    // Finance service gateway (authenticated)
    Route::prefix('finance')->group(function () {
        Route::any('{endpoint}', [ApiGatewayController::class, 'handleFinanceService'])
            ->where('endpoint', '.*');
    });

    // Role-based routes (keep your role middleware and method as is)
    Route::middleware('role:Teacher')->prefix('teacher')->group(function () {
        Route::any('{endpoint}', [ApiGatewayController::class, 'handleTeacherService'])
            ->where('endpoint', '.*');
    });

    Route::middleware('role:student')->prefix('student')->group(function () {
        Route::any('{endpoint}', [ApiGatewayController::class, 'handleStudentService'])
            ->where('endpoint', '.*');
    });

    Route::middleware('role:hr')->prefix('hr')->group(function () {
        Route::any('{endpoint}', [ApiGatewayController::class, 'handleHRService'])
            ->where('endpoint', '.*');
    });

    // User management routes (keep POST as in original)
    Route::prefix('users')->group(function () {
        Route::post('/', [UserController::class, 'index']);
        Route::post('store', [UserController::class, 'store']);
        Route::post('show', [UserController::class, 'show']);
        Route::post('update', [UserController::class, 'update']);
        Route::post('delete', [UserController::class, 'delete']);
    });

    // Permission management routes
    Route::prefix('permissions')->group(function () {
        Route::post('/', [PermissionController::class, 'index']);
        Route::post('store', [PermissionController::class, 'store']);
        Route::post('show', [PermissionController::class, 'show']);
        Route::post('update', [PermissionController::class, 'update']);
        Route::post('delete', [PermissionController::class, 'delete']);
    });

    // Role management routes
    Route::prefix('roles')->group(function () {
        Route::post('/', [RoleController::class, 'index']);
        Route::post('store', [RoleController::class, 'store']);
        Route::post('show', [RoleController::class, 'show']);
        Route::post('update', [RoleController::class, 'update']);
        Route::post('delete', [RoleController::class, 'delete']);
    });

});

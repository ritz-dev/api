<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ApiGatewayController;
use App\Http\Controllers\PermissionController;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

Route::get('/', function () {
    return view('welcome');
});

// Health check endpoint
Route::get('/health', function () {
    try {
        // Check database connection
        DB::connection()->getPdo();
        $dbStatus = 'connected';
    } catch (\Exception $e) {
        $dbStatus = 'disconnected';
    }
    
    return response()->json([
        'status' => 'ok',
        'timestamp' => now()->toISOString(),
        'service' => 'SMS API',
        'version' => '1.0.0',
        'database' => $dbStatus,
        'environment' => app()->environment()
    ], 200);
});

// Simple ping endpoint
Route::get('/ping', function () {
    return response()->json(['message' => 'pong'], 200);
});

// Test academic API health endpoint
Route::get('/test-academic-health', function () {
    try {
        $academicUrl = env('ACADEMIC_URL', 'http://academic-service.academic.svc.cluster.local/gateway');
        $healthUrl = $academicUrl . '/health';
        
        // Call academic API health endpoint
        $response = Http::timeout(10)->get($healthUrl);
        
        return response()->json([
            'sms_api_status' => 'ok',
            'academic_api_call' => [
                'url' => $healthUrl,
                'status_code' => $response->status(),
                'response' => $response->json(),
                'success' => $response->successful()
            ],
            'timestamp' => now()->toISOString()
        ], 200);
        
    } catch (\Exception $e) {
        return response()->json([
            'sms_api_status' => 'ok',
            'academic_api_call' => [
                'url' => $healthUrl ?? 'unknown',
                'error' => $e->getMessage(),
                'success' => false
            ],
            'timestamp' => now()->toISOString()
        ], 200);
    }
});

// Test teacher API health endpoint
Route::get('/test-teacher-health', function () {
    try {
        $teacherUrl = env('TEACHER_URL', 'http://teacher-service.teacher.svc.cluster.local/gateway');
        $healthUrl = $teacherUrl . '/health';
        
        // Call teacher API health endpoint
        $response = Http::timeout(10)->get($healthUrl);
        
        return response()->json([
            'sms_api_status' => 'ok',
            'teacher_api_call' => [
                'url' => $healthUrl,
                'status_code' => $response->status(),
                'response' => $response->json(),
                'success' => $response->successful()
            ],
            'timestamp' => now()->toISOString()
        ], 200);
        
    } catch (\Exception $e) {
        return response()->json([
            'sms_api_status' => 'ok',
            'teacher_api_call' => [
                'url' => $healthUrl ?? 'unknown',
                'error' => $e->getMessage(),
                'success' => false
            ],
            'timestamp' => now()->toISOString()
        ], 200);
    }
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

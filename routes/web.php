<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;

Route::get('/', function () {
    return view('welcome');
});

// Health check endpoints
Route::get('/health', function () {
    try {
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

Route::get('/ping', function () {
    return response()->json(['message' => 'pong'], 200);
});

Route::get('/api/health', function () {
    try {
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

Route::get('/api/ping', function () {
    return response()->json(['message' => 'pong'], 200);
});

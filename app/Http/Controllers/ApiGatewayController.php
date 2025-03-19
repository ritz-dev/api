<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ApiGatewayController extends Controller
{
    protected $services;

    public function __construct()
    {
        $this->services = [
            'user-management' => env('USER_MANAGEMENT_URL'),
            'academic' => env('ACADEMIC_URL'),
            'finance' => env('FINANCE_URL'),
        ];
    }

    public function handleUserManagementService(Request $request, $endpoint)
    {
        return $this->forwardRequest('user-management', $endpoint, $request);
    }

    public function handleAcademicService(Request $request, $endpoint)
    {
        return $this->forwardRequest('academic', $endpoint, $request);
    }

    public function handleFinanceService(Request $request, $endpoint)
    {
        return $this->forwardRequest('finance', $endpoint, $request);
    }

    // private function forwardRequest($serviceKey, $endpoint, Request $request)
    // {
    //     if (!isset($this->services[$serviceKey]) || empty($this->services[$serviceKey])) {
    //         return response()->json(['error' => 'Service URL not configured'], 500);
    //     }

    //     $url = rtrim($this->services[$serviceKey], '/') . '/' . ltrim($endpoint, '/');
        
    //     Log::info("Request", $request->all());

    //     $response = Http::post($url, $request->all());
        
    //     if ($response->successful()) {
    //         $data = $response->json();

    //         Log::info("Response received", [
    //             'status' => $response->status(),
    //             'headers' => $response->headers(),
    //             'body' => $response->body(),
    //             'data' => $data
    //         ]);
    //         // Handle successful authentication

    //         return response($response->body(), $response->status());
    //     } else {
    //         // Handle authentication failure
    //         $error = $response->json('error');
    //         // Log or display the error message
    //         Log::info("Response received", [
    //             'status' => $response->status(),
    //             'headers' => $response->headers(),
    //             'body' => $response->body(),
    //             'error' => $error
    //         ]);

    //         return response()->json(['error' => 'Service request failed'], 500);
    //     }

    // }

    private function forwardRequest($serviceKey, $endpoint, Request $request)
{
    if (!isset($this->services[$serviceKey]) || empty($this->services[$serviceKey])) {
        return response()->json(['error' => 'Service URL not configured'], 500);
    }

    $url = rtrim($this->services[$serviceKey], '/') . '/' . ltrim($endpoint, '/');
    $method = strtolower($request->method());

    Log::info("Request Sent", [
        'method' => strtoupper($method),
        'url' => $url,
        'data' => $request->all()
    ]);

    // Handle all request methods dynamically
    $response = Http::$method($url, $request->all());

    if ($response->successful()) {
        $data = $response->json();

        Log::info("Response received", [
            'status' => $response->status(),
            'headers' => $response->headers(),
            'body' => $response->body(),
            'data' => $data
        ]);

        return response($response->body(), $response->status());
    } else {
        $error = $response->json('error');

        Log::error("Response Error", [
            'status' => $response->status(),
            'headers' => $response->headers(),
            'body' => $response->body(),
            'error' => $error
        ]);

        return response()->json(['error' => 'Service request failed'], 500);
    }
}


}

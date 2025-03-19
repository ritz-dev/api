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

    private function forwardRequest($serviceKey, $endpoint, Request $request)
    {
        if (!isset($this->services[$serviceKey]) || empty($this->services[$serviceKey])) {
            return response()->json(['error' => 'Service URL not configured'], 500);
        }

        $url = rtrim($this->services[$serviceKey], '/') . '/' . ltrim($endpoint, '/');
        
        // Log method and URL
        // Log::info("Forwarding request", [
        //     'method' => $request->method(),
        //     'url' => $url,
        //     'headers' => $request->headers->all(),
        //     'query' => $request->query(),
        //     'body' => $request->all()
        // ]);

        // try {
        //     // Use a basic GET request first (works as per your test)
        //     if ($request->method() === 'GET') {
        //         $response = Http::withHeaders([
        //             'Accept' => 'application/json',
        //         ])->get($url, $request->query());
        //     } else {
        //         // Forward request with proper method, headers, and body
        //         // $response = Http::withHeaders($request->headers->all())
        //         //     ->put('https://academic-main-nvmcwz.laravel.cloud/gateway/hello-post');
        //         // $response = Http::withHeaders($request->headers->all())
        //         //     ->send($request->method(), 'https://academic-main-nvmcwz.laravel.cloud/gateway/hello-post', [
        //         //         'json' => $request->all(), // Send request body
        //         //     ]);

        //         // $headers = [
        //         //     'Authorization' => $request->header('Authorization'),
        //         //     'Accept' => 'application/json',
        //         //     'Content-Type' => 'application/json',
        //         // ];
            
        //         // $response = Http::post('https://academic-main-nvmcwz.laravel.cloud/api/gateway/hello-post', [
        //         //     'json' => $request->all(),
        //         // ]);

        //         $response = Http::withHeaders([
        //             'Authorization' => $request->header('Authorization'),
        //             'Accept' => 'application/json',
        //             'Content-Type' => 'application/json',
        //         ])->post($url);
        //     }

        //     // Log response
        //     Log::info("Response received", [
        //         'status' => $response->status(),
        //         'headers' => $response->headers(),
        //         'body' => $response->body()
        //     ]);

        //     return response($response->body(), $response->status())
        //         ->withHeaders($response->headers());
        // } catch (\Exception $e) {
        //     Log::error("Error forwarding request to $url: " . $e->getMessage());
        //     return response()->json(['error' => 'Service request failed'], 500);
        // }



        $response = Http::withHeaders([
            'Authorization' => $request->header('Authorization'),
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ])->post('https://academic-main-nvmcwz.laravel.cloud/gateway/hello-post');

         // Log response
         Log::info("Response received", [
            'status' => $response->status(),
            'headers' => $response->headers(),
            'body' => $response->body()
        ]);

    }

}

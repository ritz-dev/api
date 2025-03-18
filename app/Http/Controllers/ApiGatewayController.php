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
    Log::info("Forwarding request", [
        'method' => $request->method(),
        'url' => $url,
        'headers' => $request->headers->all(),
        'query' => $request->query(),
        'body' => $request->all()
    ]);

    try {
        // Merge headers and ensure Accept is set
        $httpClient = Http::withHeaders(array_merge(
            $request->headers->all(),
            [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                'Authorization' => $request->header('Authorization') ?? ''
            ]
        ));

        switch ($request->method()) {
            case 'GET':
                $response = $httpClient->get($url, $request->query());
                break;
            case 'POST':
                $response = $httpClient->post($url, $request->all());
                break;
            case 'PUT':
                $response = $httpClient->put($url, $request->all());
                break;
            case 'PATCH':
                $response = $httpClient->patch($url, $request->all());
                break;
            case 'DELETE':
                $response = $httpClient->delete($url, $request->all());
                break;
            default:
                return response()->json(['error' => 'Invalid HTTP method'], 405);
        }

        return response($response->body(), $response->status())
            ->withHeaders($response->headers());
    } catch (\Exception $e) {
        Log::error("Error forwarding request to $url: " . $e->getMessage());
        return response()->json(['error' => 'Service request failed'], 500);
    }
}
}

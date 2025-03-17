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
        // Ensure the service URL exists
        if (!isset($this->services[$serviceKey]) || empty($this->services[$serviceKey])) {
            return response()->json(['error' => 'Service URL not configured'], 500);
        }

        $url = $this->services[$serviceKey] . '/' . $endpoint;

        return Http::get($url);

        try {
            $response = Http::withHeaders($request->headers->all())
                ->send($request->method(), $url, [
                    'query' => $request->query(),
                    'json' => $request->all(),
                ]);

            // Optionally log the request and response for debugging
            Log::info("Forwarded request to $url", [
                'request' => $request->all(),
                'response' => $response->body(),
            ]);

            return response($response->body(), $response->status())
                ->withHeaders($response->headers());
        } catch (\Exception $e) {
            // Log the error for further debugging
            Log::error("Error forwarding request to $url: " . $e->getMessage());
            return response()->json(['error' => 'Service request failed'], 500);
        }
    }
}

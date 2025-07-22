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
                'user' => env('USER_URL'),
                'teacher' => env('TEACHER_URL'),
                'academic' => env('ACADEMIC_URL'),
                'finance' => env('FINANCE_URL'),
            ];
        }

        public function handleUserService(Request $request, $endpoint)
        {
            return $this->forwardRequest('user', $endpoint, $request);
        }

        public function handleAcademicService(Request $request, $endpoint)
        {
            return $this->forwardRequest('academic', $endpoint, $request);
        }

        public function handleTeacherService(Request $request, $endpoint)
        {
            $user = auth()->user();

            if (!$user || !$user->employee_slug) {
                return response()->json(['error' => 'Authenticated user not found'], 403);
            }

            // Add `owner_slug` to the request data
            if ($request->isMethod('get')) {
                // Append to query parameters
                $request->query->add(['owner_slug' => $user->employee_slug]);
            } else {
                // Append to body parameters
                $request->merge(['owner_slug' => $user->employee_slug]);
            }

            return $this->forwardRequest('teacher', $endpoint, $request);
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
            $method = strtolower($request->method());

            // Handle all request methods dynamically
            $response = Http::withHeaders([
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ])->{$method}($url, $request->all());

            if ($response->successful()) {
                $data = $response->json();

                return response($response->body(), $response->status())->header('Content-Type', 'application/json');
            } else {
                $errorBody = $response->body(); // Log the full response body
            
                return response()->json([
                    'error' => $response->json('error') ?? 'An unknown error occurred',
                    'details' => $errorBody // Include full body for debugging
                ], $response->status());
            }
        }

        // private function forwardRequest($serviceKey, $endpoint, Request $request)
        // {
        //     if (!isset($this->services[$serviceKey]) || empty($this->services[$serviceKey])) {
        //         return response()->json(['error' => 'Service URL not configured'], 500);
        //     }

        //     $url = rtrim($this->services[$serviceKey], '/') . '/' . ltrim($endpoint, '/');
        //     $method = strtolower($request->method());

        //     $http = Http::withHeaders($request->headers->all());

        //     Log::info("Forwarding request", [
        //         'method' => $method,
        //         'url' => $url,
        //         'query' => $request->query(),
        //         'body' => $request->all(),
        //         'headers' => $request->headers->all()
        //     ]);

        //     if ($method === 'get') {
        //         $response = $http->get($url, $request->query());
        //     } else {
        //         $response = $http->{$method}($url, $request->all());
        //     }

        //     if ($response->successful()) {
        //         return response($response->body(), $response->status())
        //             ->header('Content-Type', 'application/json');
        //     } else {
        //         return response()->json([
        //             'error' => $response->json('error') ?? 'Unknown error',
        //             'details' => $response->body(),
        //         ], $response->status());
        //     }
        // }
    }

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;

class ApiTesterController extends Controller
{
    /**
     * Test an API endpoint
     */
    public function test(Request $request)
    {
        // Validate the request
        $validator = Validator::make($request->all(), [
            'method' => 'required|in:GET,POST,PUT,DELETE,PATCH',
            'url' => 'required|url',
            'content_type' => 'nullable|string',
            'body' => 'nullable|string',
            'headers' => 'nullable|array',
            'headers.*.key' => 'nullable|string',
            'headers.*.value' => 'nullable|string',
            'params' => 'nullable|array',
            'params.*.key' => 'nullable|string',
            'params.*.value' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Please fix the validation errors and try again.');
        }

        try {
            // Prepare headers
            $headers = [];
            if ($request->has('headers')) {
                foreach ($request->input('headers', []) as $header) {
                    if (!empty($header['key']) && !empty($header['value'])) {
                        $headers[$header['key']] = $header['value'];
                    }
                }
            }

            // Add content type if specified
            if ($request->filled('content_type') && in_array($request->method, ['POST', 'PUT', 'PATCH'])) {
                $headers['Content-Type'] = $request->input('content_type');
            }

            // Prepare query parameters
            $queryParams = [];
            if ($request->has('params')) {
                foreach ($request->input('params', []) as $param) {
                    if (!empty($param['key']) && !empty($param['value'])) {
                        $queryParams[$param['key']] = $param['value'];
                    }
                }
            }

            // Build the URL with query parameters
            $url = $request->input('url');
            if (!empty($queryParams)) {
                $url .= '?' . http_build_query($queryParams);
            }

            // Prepare the HTTP client
            $httpClient = Http::withHeaders($headers)
                ->timeout(30) // 30 second timeout
                ->withoutVerifying(); // Skip SSL verification for testing

            // Record start time
            $startTime = microtime(true);

            // Make the request based on method
            $method = strtoupper($request->input('method'));
            $body = $request->input('body');

            switch ($method) {
                case 'GET':
                    $response = $httpClient->get($url);
                    break;
                case 'POST':
                    if ($request->input('content_type') === 'application/json' && $body) {
                        $response = $httpClient->withBody($body, 'application/json')->post($url);
                    } else {
                        $response = $httpClient->post($url, $body ? json_decode($body, true) : []);
                    }
                    break;
                case 'PUT':
                    if ($request->input('content_type') === 'application/json' && $body) {
                        $response = $httpClient->withBody($body, 'application/json')->put($url);
                    } else {
                        $response = $httpClient->put($url, $body ? json_decode($body, true) : []);
                    }
                    break;
                case 'DELETE':
                    $response = $httpClient->delete($url);
                    break;
                case 'PATCH':
                    if ($request->input('content_type') === 'application/json' && $body) {
                        $response = $httpClient->withBody($body, 'application/json')->patch($url);
                    } else {
                        $response = $httpClient->patch($url, $body ? json_decode($body, true) : []);
                    }
                    break;
                default:
                    throw new \Exception('Unsupported HTTP method');
            }

            // Calculate response time
            $responseTime = round((microtime(true) - $startTime) * 1000, 2);

            // Prepare response data
            $responseData = [
                'status' => $response->status(),
                'time' => $responseTime,
                'headers' => $response->headers(),
                'body' => $response->body(),
            ];

            // Try to decode JSON response
            if ($response->header('Content-Type') && 
                (strpos($response->header('Content-Type'), 'application/json') !== false || 
                 strpos($response->header('Content-Type'), 'text/json') !== false)) {
                try {
                    $jsonBody = $response->json();
                    $responseData['body'] = $jsonBody;
                } catch (\Exception $e) {
                    // Keep original body if JSON parsing fails
                }
            }

            return view('api-tester', [
                'response' => $responseData
            ])->withInput();

        } catch (\Exception $e) {
            // Handle errors
            $errorResponse = [
                'status' => 0,
                'time' => 0,
                'headers' => [],
                'body' => [
                    'error' => true,
                    'message' => $e->getMessage(),
                    'type' => 'Request Error'
                ]
            ];

            return view('api-tester', [
                'response' => $errorResponse
            ])->withInput()
              ->with('error', 'Request failed: ' . $e->getMessage());
        }
    }

    /**
     * Show the API tester page
     */
    public function index()
    {
        return view('api-tester');
    }
}
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ApiGatewayController extends Controller
{
    protected $services = [
        'user-management' => 'http://user_server/gateway',
        'academic' => 'http://academic_server/gateway',
        'finance'=> 'http://finance_server/gateway',
    ];

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
        $url = $this->services[$serviceKey] . '/' . $endpoint;

        $response = Http::withHeaders($request->headers->all())
            ->send($request->method(), $url, [
                'query' => $request->query(),
                'json' => $request->all(),
            ]);

        return response($response->body(), $response->status())
            ->withHeaders($response->headers());
    }
}

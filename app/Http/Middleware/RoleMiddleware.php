<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $role): Response
    {
        $user = auth()->user();

        if (!$user || $user->role !== $role) {
            return response()->json(['message' => 'Unauthorized. Only '.$role.'s allowed.'], 403);
        }

        return $next($request);
    }
}

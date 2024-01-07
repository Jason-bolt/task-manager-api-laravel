<?php

namespace App\Http\Middleware;

use App\Models\UserActivityLogs;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UserActivities
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            return response()->json([
                'message' => 'User not logged in',
            ], 403);
        }

        UserActivityLogs::create([
            'authenticated_user_id' => auth()->id(),
            'route_name' => $request->getUri(),
            'http_method' => $request->getMethod()
        ]);
        
        return $next($request);
    }
}

<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class JsonApi
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (!$request->expectsJson()) {
            return response([
                'code' => 406,
                'message' => 'Not Acceptable (expects json)'
            ], 406);
        }

        return $next($request);
    }
}

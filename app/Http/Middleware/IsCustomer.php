<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class IsCustomer
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if ($request->user() && ( $request->user()->type === 0)) {
            return $next($request);
        }

        return $request->expectsJson()
            ? response([
                'message' => 'Access Denied.',
            ], 403)
            : abort(403, 'Access Denied.');
    }
    
}

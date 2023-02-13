<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class IsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string[]  ...$guards
     * @return mixed
     */
    public function handle(Request $request, Closure $next, ...$guards)
    {
        if ($request->user() && ($request->user()?->role === 1 || $request->user()->type === 2)) {
            return $next($request);
        }

        return $request->expectsJson()
            ? response([
                'message' => 'Access Denied.',
            ], 403)
            : abort(403, 'Access Denied.');
    }
}

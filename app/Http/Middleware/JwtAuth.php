<?php

namespace App\Http\Middleware;

use Closure;
use Tymon\JWTAuth\Middleware\GetUserFromToken as defaultMiddlewareJwt;

class JwtAuth extends defaultMiddlewareJwt
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        return $next($request);
    }
}

<?php

namespace App\Http\Middleware;

use Closure;

/**
 * Class UpdateAuthorizationBearerToken
 * @package App\Http\Middleware
 */
class UpdateAuthorizationBearerToken
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
        if ($request->headers->get("Authorization") && strpos($request->headers->get("Authorization"), "Bearer ") === false) {
            $request->headers->set("Authorization", "Bearer ".$request->headers->get("Authorization"));
        }

        return $next($request);
    }
}

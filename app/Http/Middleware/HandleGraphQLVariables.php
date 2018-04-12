<?php

namespace App\Http\Middleware;

use Closure;

/**
 * Class HandleGraphQLVariables
 * @package App\Http\Middleware
 */
class HandleGraphQLVariables
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
        if (request()->all()) {
            \Log::info(request()->all());
        }

        return $next($request);
    }
}

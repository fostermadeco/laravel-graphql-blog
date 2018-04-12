<?php

namespace App\Http\Middleware;

use Closure;

/**
 * Class HandleFileUploads
 * @package App\Http\Middleware
 */
class HandleFileUploads
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
        if (!empty($_FILES)) {
            \App::make("App\Http\Controllers\UploadController")->index();
        }

        return $next($request);
    }
}

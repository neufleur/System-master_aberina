<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckUserRole
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
           // メンテナンスモードチェックのロジック
           if (app()->isDownForMaintenance() && !(auth()->check() && $request->user()->isAdmin()))
            {
                return new Response('Service is under maintenance.', 503);
            }
            return $next($request);

    }
}

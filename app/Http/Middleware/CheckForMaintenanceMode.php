<?php

namespace App\Http\Middleware;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

use Illuminate\Foundation\Http\Middleware\CheckForMaintenanceMode as Middleware;

class CheckForMaintenanceMode extends Middleware
{
    /**
     * The URIs that should be reachable while maintenance mode is enabled.
     *
     * @var array
     */
    protected $except = [
        // 例: 'api/*',
    ];

    public function handle($request, Closure $next)
    {
           // メンテナンスモードチェックのロジック
           if (app()->isDownForMaintenance() && !(auth()->check() && $request->user()->isAdmin()))
            {
                return new Response('Service is under maintenance.', 503);
            }
            return parent::handle($request, $next);

    }
}
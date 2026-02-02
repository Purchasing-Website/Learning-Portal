<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetSessionCookieByHost
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, $next)
    {
        $adminHost = config('app.admin_url');

        if ($request->getHost() === $adminHost) {
            config(['session.cookie' => 'admin_session']);
        } else {
            config(['session.cookie' => 'main_session']);
        }

        // Keep cookies separate between domains
        config(['session.domain' => null]);

        return $next($request);
    }

}

<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RedirectDefaultLanguage
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->routeIs('front.*')) {
            if ($request->route()->parameter('lang') === defaultLanguage()) {
                return redirect()->route($request->route()->getName());
            }

            if (
                $request->route()->parameter('lang') !== null
                && !in_array($request->route()->parameter('lang'), activeLanguages()->toArray())
            ) {
                return redirect()->route($request->route()->getName());
            }
        }

        return $next($request);
    }
}

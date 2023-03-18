<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->routeIs('front.*')) {
            if (
                $request->route()->parameter('lang') !== null
                && !activeLanguages()->contains($request->route()->parameter('lang'))
            ) {
                return redirect()->route('front.maps.index', ['lang' => defaultLanguage()]);
            }

            if ($request->route()->parameter('lang') !== null) {
                app()->setLocale($request->route()->parameter('lang'));
            }
        }

        if (
            $request->ajax()
            && $request->hasHeader('X-Requested-Locale')
            && activeLanguages()->contains($request->header('X-Requested-Locale'))
        ) {
            app()->setLocale($request->header('X-Requested-Locale'));
        }

        return $next($request);
    }
}

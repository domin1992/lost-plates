<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Localization
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->header('Accept-Language')) {
            $allowedLocaleCollectionKeys = collect(config('app.allowed_locale'))->keys()->toArray();

            $localization = in_array($request->header('Accept-Language'), $allowedLocaleCollectionKeys, true)
                ? $request->header('Accept-Language')
                : $allowedLocaleCollectionKeys[0];

            app()->setLocale($localization);
        }

        return $next($request);
    }
}

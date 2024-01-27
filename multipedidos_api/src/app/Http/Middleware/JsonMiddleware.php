<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class JsonMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next) : Response
    {
        $request->headers->set('Accept', 'application/json');

        // if ($request->is('api/docs') || $request->is('docs/api-docs.json')) {
        //     return $next($request);
        // }

        // if (! $request->isJson() || ! $request->wantsJson()) {
        //     return response()->json(['message' => 'Only application/json content type is accepted'], Response::HTTP_UNSUPPORTED_MEDIA_TYPE);
        // }

        return $next($request);
    }
}

<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class UpdatedBy
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check() && ($request->isMethod('post') || $request->isMethod('put'))) {
//            $request->merge(['user_id' => Auth::id()]);
//            Log::info('Request after merge in middleware:', $request->all());
        }

        return $next($request);
    }

}

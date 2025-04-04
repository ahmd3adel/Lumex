<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckStatus
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        // تحقق إذا كان المستخدم مسجل دخول
        if (!$request->user()) {
            return redirect()->route('login')->with('error', 'Please log in to continue.');
        }

        // تحقق من حالة المستخدم
        if ($request->user()->status !== 'active') {
            // تسجيل خروج المستخدم
            auth()->logout();

            // إعادة توجيهه إلى صفحة تسجيل الدخول مع رسالة
            return redirect()->route('login')->with('error', 'Your account has been deactivated. Please contact support.');
        }

        // السماح بالوصول إذا كانت الحالة "active"
        return $next($request);
    }
}

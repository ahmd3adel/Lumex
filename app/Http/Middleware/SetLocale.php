<?php

namespace App\Http\Middleware;
use Closure;
use Illuminate\Support\Facades\App;

class SetLocale
{
    public function handle($request, Closure $next)
    {
        // الحصول على اللغة من الرابط (prefix)
        $locale = $request->segment(1);

        // التحقق من اللغات المدعومة
        if (in_array($locale, ['en', 'ar'])) {
            App::setLocale($locale); // ضبط اللغة في Laravel

            // ضبط الإعدادات المحلية للنظام
            $localeMapping = [
                'en' => 'en_US.UTF-8',
                'ar' => 'ar_EG.UTF-8',
            ];
            setlocale(LC_ALL, $localeMapping[$locale]);
        } else {
            App::setLocale('en');
            setlocale(LC_ALL, 'en_US.UTF-8');
        }

        return $next($request);
    }
}

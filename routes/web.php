<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use App\Http\Controllers\StoreController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
//
//Route::get('/', function () {
//    return view('welcome');
//});
//


Route::group(
    [
        'prefix' => LaravelLocalization::setLocale(),
        'middleware' => ['localeSessionRedirect', 'localizationRedirect', 'localeViewPath']

    ],
    function () {
        Route::get('/dashboard', function () {
            return view('dashboard');
        })->middleware(['auth', 'verified'])->name('dashboard');

        Route::get('/change-language', function (Illuminate\Http\Request $request) {
            $locale = $request->get('locale', 'en'); // تحديد اللغة الافتراضية
            if (in_array($locale, ['en', 'ar'])) { // التحقق من أن اللغة مدعومة
                app()->setLocale($locale); // ضبط اللغة
                session(['locale' => $locale]); // حفظ اللغة في الجلسة
                \Log::info('Locale changed to: ' . $locale); // تسجيل اللغة الجديدة
            }
            // إعادة التوجيه للرابط الجديد مع اللغة
            return redirect()->to(LaravelLocalization::getLocalizedURL($locale));
        })->name('changeLanguage');

        require __DIR__.'/auth.php';
        require __DIR__.'/stores.php';
        require __DIR__.'/users.php';
        require __DIR__.'/clients.php';
        require __DIR__.'/products.php';
    }
);






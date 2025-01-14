<?php

use App\Http\Controllers\front\HomeController;
use App\Http\Controllers\front\ProductController;
use Illuminate\Support\Facades\Route;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

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

        Route::get('/' , [HomeController::class , 'index']);
        Route::get('products' , [ProductController::class , 'index'])->name('front.products.index');
        Route::get('products/{product:name}' , [ProductController::class , 'show'])->name('front.products.show');
        Route::resource('cart' , \App\Http\Controllers\front\CartController::class);
        require __DIR__.'/auth.php';
        Route::middleware('auth')->group(function (){
        Route::group(['prefix' => 'accounts'] , function (){
            require __DIR__.'/stores.php';
            require __DIR__.'/users.php';
            require __DIR__.'/clients.php';
            require __DIR__.'/products.php';
        });

        });

    }
);






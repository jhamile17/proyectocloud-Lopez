<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    public const HOME = '/admin';

    public function boot(): void
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });

        $this->routes(function () {
            Route::middleware('web')->group(base_path('routes/web.php'));

        if (file_exists(base_path('routes/api.php'))) {
            Route::prefix('api')
                ->middleware('api')
                ->group(base_path('routes/api.php'));

        }
        
            Route::get('/', fn() => view('welcome'))->name('home');

            Route::middleware(['auth','admin'])->prefix('admin')->name('admin.')->group(function () {
                Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
                // Aquí luego irán:
                // Route::resource('categories', CategoryController::class);
                // Route::resource('brands', BrandController::class);
                // Route::resource('products', ProductController::class);
            });
    });
    }
}


<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Gate;
use Carbon\Carbon;
use App\Models\Product;
use App\Models\Brand;
use App\Models\Category;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Carbon::setLocale('km');
        $locale = session('locale', config('app.locale'));
        App::setLocale($locale);

        Gate::before(function ($user, $ability) {
            return $user->hasRole('superadmin') ||
                $user->hasRole('super admin') ||
                $user->hasRole('Super Admin') ||
                $user->hasRole('SuperAdmin') ||
                $user->hasRole('admin') ||
                $user->hasRole('Admin') ? true : null;
        });

        View::composer('*', function ($view) {
            $view->with('products', Product::all());
            $view->with('brands', Brand::all());
            $view->with('categories', Category::all());
        });
    }
}

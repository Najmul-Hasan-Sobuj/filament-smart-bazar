<?php

namespace App\Providers;

use App\Models\Food;
use App\Observers\FoodObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Food::observe(FoodObserver::class);
    }
}

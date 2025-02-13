<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Tinify\Tinify;

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
        Tinify::setKey(env('TINIFY_API_KEY'));
    }
}

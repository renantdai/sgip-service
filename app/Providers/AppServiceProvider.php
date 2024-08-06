<?php

namespace App\Providers;

use App\Repositories\SMSEloquentORM;
use App\Repositories\SMSRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(
            SMSRepositoryInterface::class,
            SMSEloquentORM::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}

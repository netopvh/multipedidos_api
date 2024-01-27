<?php

namespace App\Providers;

use App\Services\Contracts\{
    CarServiceInterface,
    UserServiceInterface
};
use App\Services\{
    CarServiceImp,
    UserServiceImp
};
use Illuminate\Support\ServiceProvider;

class ServicesServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register() : void
    {
        $this->app->bind(
            CarServiceInterface::class,
            CarServiceImp::class
        );

        $this->app->bind(
            UserServiceInterface::class,
            UserServiceImp::class
        );
    }

    /**
     * Bootstrap services.
     */
    public function boot() : void
    {
        //
    }
}

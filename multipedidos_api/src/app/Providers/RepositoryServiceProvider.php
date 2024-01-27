<?php

namespace App\Providers;

use App\Repositories\{
    CarRepositoryImp,
    UserRepositoryImp
};
use App\Repositories\Contracts\{
    CarRepositoryInterface,
    UserRepositoryInterface
};
use App\Repositories\Core\{
    BaseRepositoryImp,
    BaseRepositoryInterface
};
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register() : void
    {
        $this->app->bind(
            BaseRepositoryInterface::class,
            BaseRepositoryImp::class
        );
        $this->app->bind(
            CarRepositoryInterface::class,
            CarRepositoryImp::class
        );

        $this->app->bind(
            UserRepositoryInterface::class,
            UserRepositoryImp::class
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

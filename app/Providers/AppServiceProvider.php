<?php

namespace App\Providers;

use App\Repositories\V1\Auth\AuthRepository;
use App\Repositories\V1\Blog\BlogRepository;
use App\Repositories\V1\Cliente\ClienteRepository;
use App\Repositories\V1\Contracts\AuthRepositoryInterface;
use App\Repositories\V1\Contracts\BlogRepositoryInterface;
use App\Repositories\V1\Contracts\ClienteRepositoryInterface;
use App\Repositories\V1\Contracts\ProductoRepositoryInterface;
use App\Repositories\V1\Contracts\UserRepositoryInterface;
use App\Repositories\V1\Producto\ProductoRepository;
use App\Repositories\V1\User\UserRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(AuthRepositoryInterface::class, AuthRepository::class);
        $this->app->bind(BlogRepositoryInterface::class, BlogRepository::class);
        $this->app->bind(ClienteRepositoryInterface::class, ClienteRepository::class);
        $this->app->bind(ProductoRepositoryInterface::class, ProductoRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}

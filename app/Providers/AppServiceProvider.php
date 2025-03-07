<?php

namespace App\Providers;

use App\Repositories\Auth\AuthRepository;
use App\Repositories\Auth\AuthRepositoryInterface;
use App\Repositories\Blog\BlogRepository;
use App\Repositories\Blog\BlogRepositoryInterface;
use App\Repositories\Cliente\ClienteRepository;
use App\Repositories\Cliente\ClienteRepositoryInterface;
use App\Repositories\Producto\ProductoRepository;
use App\Repositories\Producto\ProductoRepositoryInterface;
use App\Repositories\User\UserRepository;
use App\Repositories\User\UserRepositoryInterface;
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

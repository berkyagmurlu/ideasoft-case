<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Interfaces\{
    UserRepositoryInterface,
    CategoryRepositoryInterface,
    ProductRepositoryInterface,
    OrderRepositoryInterface,
    DiscountRepositoryInterface,
    AuthServiceInterface,
    OrderServiceInterface,
    DiscountServiceInterface
};
use App\Repositories\{
    UserRepository,
    CategoryRepository,
    ProductRepository,
    OrderRepository,
    DiscountRepository
};
use App\Services\{
    AuthService,
    OrderService,
    DiscountService
};

class RepositoryServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Repositories
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(CategoryRepositoryInterface::class, CategoryRepository::class);
        $this->app->bind(ProductRepositoryInterface::class, ProductRepository::class);
        $this->app->bind(OrderRepositoryInterface::class, OrderRepository::class);
        $this->app->bind(DiscountRepositoryInterface::class, DiscountRepository::class);

        // Services
        $this->app->bind(AuthServiceInterface::class, AuthService::class);
        $this->app->bind(OrderServiceInterface::class, OrderService::class);
        $this->app->bind(DiscountServiceInterface::class, DiscountService::class);
    }
} 
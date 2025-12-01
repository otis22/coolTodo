<?php

declare(strict_types=1);

namespace App\Infrastructure\Providers;

use App\Domain\Repositories\TodoRepositoryInterface;
use App\Domain\UseCases\CreateTodoUseCase;
use App\Domain\UseCases\DeleteCompletedTodosUseCase;
use App\Domain\UseCases\DeleteTodoUseCase;
use App\Domain\UseCases\GetTodosUseCase;
use App\Domain\UseCases\ToggleTodoStatusUseCase;
use App\Domain\UseCases\UpdateTodoUseCase;
use App\Infrastructure\Repositories\TodoRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // Bind Repository Interface
        $this->app->singleton(TodoRepositoryInterface::class, TodoRepository::class);

        // Bind Use Cases
        $this->app->singleton(GetTodosUseCase::class, function ($app) {
            return new GetTodosUseCase($app->make(TodoRepositoryInterface::class));
        });

        $this->app->singleton(CreateTodoUseCase::class, function ($app) {
            return new CreateTodoUseCase($app->make(TodoRepositoryInterface::class));
        });

        $this->app->singleton(UpdateTodoUseCase::class, function ($app) {
            return new UpdateTodoUseCase($app->make(TodoRepositoryInterface::class));
        });

        $this->app->singleton(ToggleTodoStatusUseCase::class, function ($app) {
            return new ToggleTodoStatusUseCase($app->make(TodoRepositoryInterface::class));
        });

        $this->app->singleton(DeleteTodoUseCase::class, function ($app) {
            return new DeleteTodoUseCase($app->make(TodoRepositoryInterface::class));
        });

        $this->app->singleton(DeleteCompletedTodosUseCase::class, function ($app) {
            return new DeleteCompletedTodosUseCase($app->make(TodoRepositoryInterface::class));
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}

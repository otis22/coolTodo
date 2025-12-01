<?php

declare(strict_types=1);

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Throwable;

return Application::configure(basePath: dirname(__DIR__))
    ->withProviders([
        \App\Infrastructure\Providers\AppServiceProvider::class,
    ])
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // Логирование всех исключений в канал error
        $exceptions->report(function (Throwable $e) {
            \Illuminate\Support\Facades\Log::channel('error')->error(
                $e->getMessage(),
                [
                    'exception' => get_class($e),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'trace' => $e->getTraceAsString(),
                ]
            );
        });

        // Обработка исключений для API запросов
        $exceptions->render(function (Throwable $e, \Illuminate\Http\Request $request) {
            if ($request->is('api/*') || $request->expectsJson()) {
                return response()->json([
                    'message' => $e->getMessage(),
                    'error' => config('app.debug') ? [
                        'exception' => get_class($e),
                        'file' => $e->getFile(),
                        'line' => $e->getLine(),
                    ] : null,
                ], getStatusCode($e));
            }
        });
    })->create();

/**
 * Получить HTTP статус код для исключения
 */
function getStatusCode(Throwable $e): int
{
    if ($e instanceof \Illuminate\Validation\ValidationException) {
        return 422;
    }
    if ($e instanceof \Illuminate\Database\Eloquent\ModelNotFoundException) {
        return 404;
    }
    if ($e instanceof \Illuminate\Auth\AuthenticationException) {
        return 401;
    }
    if ($e instanceof \Illuminate\Auth\Access\AuthorizationException) {
        return 403;
    }
    if ($e instanceof \Symfony\Component\HttpKernel\Exception\NotFoundHttpException) {
        return 404;
    }
    if ($e instanceof \Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException) {
        return 405;
    }
    if ($e instanceof \Symfony\Component\HttpKernel\Exception\HttpException) {
        return $e->getStatusCode();
    }

    return 500;
}








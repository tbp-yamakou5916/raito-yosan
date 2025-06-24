<?php

use App\Application;
use App\Http\Middleware\Authenticate;
use App\Http\Middleware\IpRestrictionMiddleware;
use App\Http\Middleware\RedirectIfAuthenticated;
use App\Providers\AppServiceProvider;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Route;
use Spatie\Permission\Middleware\PermissionMiddleware;
use Spatie\Permission\Middleware\RoleMiddleware;
use Spatie\Permission\Middleware\RoleOrPermissionMiddleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        using: function () {
            // 管理画面
            $user_home = AppServiceProvider::USER_HOME;
            Route::middleware('web')
                 ->as('admin.')
                 ->group(base_path('routes/admin.php'));
        },
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'auth' => Authenticate::class,
            'guest' => RedirectIfAuthenticated::class,
            'role' => RoleMiddleware::class,
            'permission' => PermissionMiddleware::class,
            'role_or_permission' => RoleOrPermissionMiddleware::class,
            // IP制限
            //'ip_restriction' => IpRestrictionMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();

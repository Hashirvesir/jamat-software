<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Spatie\Permission\Middleware\RoleMiddleware; // Changed to Middleware
use Spatie\Permission\Middleware\PermissionMiddleware; // Changed to Middleware
use Spatie\Permission\Middleware\RoleOrPermissionMiddleware; // Changed to Middleware

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'role' => RoleMiddleware::class,
            'permission' => PermissionMiddleware::class,
            'role_or_permission' => RoleOrPermissionMiddleware::class, // Added
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
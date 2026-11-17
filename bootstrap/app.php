<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'super_admin' => \App\Http\Middleware\SuperAdminMiddleware::class,
            'group_user'  => \App\Http\Middleware\GroupUserMiddleware::class,
        ]);
        
        $middleware->redirectGuestsTo(fn (Request $request) => 
            $request->is('group*') ? route('group.login') : route('admin.login')
        );
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();

<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Route;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        then: function () {
            Route::middleware('web')
                ->group(base_path('routes/perpustakaan.php'));
        },
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Redirect guest middleware ke '/' jika sudah login (route '/' punya logika role)
        $middleware->redirectGuestsTo(fn () => route('login'));
        $middleware->redirectUsersTo('/');

        // Mendaftarkan middleware untuk memblokir akses langsung tanpa referer
        $middleware->web(append: [
            \App\Http\Middleware\PreventDirectAccess::class,
        ]);

        $middleware->alias([
            'role'          => \App\Http\Middleware\RoleMiddleware::class,
            'role.perpus'   => \App\Http\Middleware\PerpusRoleMiddleware::class,
            'tkr'           => \App\Http\Middleware\TkrOnlyMiddleware::class,
            'require.email' => \App\Http\Middleware\RequireEmail::class,
            'force.password'=> \App\Http\Middleware\ForceChangePassword::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();


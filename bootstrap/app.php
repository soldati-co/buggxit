<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\AdminAuthenticate;
use App\Http\Middleware\SetCacheHeadersForStorage;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->trustProxies(at: '*');
        $middleware->web(append: [
            SetCacheHeadersForStorage::class,
        ]);

        // NOTE: this app also ships a legacy-style app/Http/Kernel.php with
        // its own $middlewareAliases (including 'admin.auth'), but Laravel
        // 11+ bootstraps via this file, not that Kernel class — it is never
        // instantiated (confirmed: the bound Illuminate\Contracts\Http\Kernel
        // is Illuminate\Foundation\Http\Kernel), so anything defined only
        // there is dead code. Register the alias here so routes can actually
        // use it.
        $middleware->alias([
            'admin.auth' => AdminAuthenticate::class,
        ]);

        // PayFast's ITN webhook is a server-to-server POST with no Laravel
        // session/CSRF token — verified instead via PayfastService's
        // signature + source-domain + confirmation checks (see routes/web.php).
        $middleware->validateCsrfTokens(except: [
            'payfast/notify',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();

    

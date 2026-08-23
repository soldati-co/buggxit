<?php

namespace App\Providers;

use App\Services\CartService;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {

    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // The app sits behind Cloudflare Tunnel -> Coolify's Caddy proxy,
        // both of which talk to the container over plain HTTP internally.
        // Whether Laravel sees the original request as secure depends on
        // X-Forwarded-Proto surviving that whole chain, which it doesn't
        // reliably -- route()/asset() were generating http:// URLs (e.g.
        // dress/hero images), which browsers then block as mixed content
        // on this https-only site. Force the scheme instead of depending
        // on proxy header forwarding we don't fully control.
        if (str_starts_with(config('app.url'), 'https://')) {
            URL::forceScheme('https');

            // forceScheme() above only affects URL *generation* (route()/asset()/
            // signed URLs) -- it does nothing for hasValidSignature(), which reads
            // the *current* request's own detected scheme via $request->url(). If
            // X-Forwarded-Proto didn't survive the proxy chain (see above), that
            // detection resolves to http, while the signed link was generated as
            // https -- the signatures then never match and every guest checkout's
            // PayFast redirect/return link 403s. Force detection to match generation.
            if (! $this->app->runningInConsole()) {
                request()->server->set('HTTPS', 'on');
                request()->headers->set('X-Forwarded-Proto', 'https');
            }
        }

        // Share cart count with all views
        View::composer('*', function ($view) {
            $view->with('cartCount', app(CartService::class)->count());
        });
    }
}
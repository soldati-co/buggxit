<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

/**
 * Baseline security headers for every response. The CSP keeps
 * 'unsafe-inline' for script-src/style-src since the app relies on inline
 * <script>/<style> blocks throughout its Blade views (no nonce plumbing
 * exists yet) — but connect-src/img-src/frame-ancestors are still locked to
 * 'self', which blocks cross-origin exfiltration (e.g. fetch()/img-beacon to
 * an attacker's domain) and clickjacking even without a full nonce-based CSP.
 */
class SecurityHeaders
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('X-Frame-Options', 'DENY');
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        $response->headers->set('Permissions-Policy', 'geolocation=(), microphone=(), camera=()');

        if ($request->secure()) {
            $response->headers->set('Strict-Transport-Security', 'max-age=31536000');
        }

        // *.elfsight.com / *.elfsightcdn.com: the Instagram Feed widget on the
        // homepage (see resources/views/components/follow-along.blade.php) —
        // Elfsight's platform.js loads widget config/images/fonts from its own
        // domains, per https://help.elfsight.com/article/1581.
        $elfsight = 'https://*.elfsight.com https://*.elfsightcdn.com';

        $response->headers->set('Content-Security-Policy', implode('; ', [
            "default-src 'self'",
            "script-src 'self' 'unsafe-inline' {$elfsight}",
            "style-src 'self' 'unsafe-inline' {$elfsight}",
            "img-src 'self' data: {$elfsight}",
            "font-src 'self' data: {$elfsight}",
            "connect-src 'self' {$elfsight}",
            "frame-src {$elfsight}",
            "form-action 'self' https://www.payfast.co.za https://sandbox.payfast.co.za",
            "frame-ancestors 'none'",
            "base-uri 'self'",
            "object-src 'none'",
        ]));

        return $response;
    }
}

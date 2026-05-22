<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SetCacheHeadersForStorage
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        // Only apply to /storage URLs (serving public disk)
        if ($request->is('storage/*')) {
            $response->headers->set('Cache-Control', 'public, max-age=31536000, immutable');
            $response->headers->set('Expires', gmdate('D, d M Y H:i:s \G\M\T', time() + 31536000));
        }

        return $response;
    }
}
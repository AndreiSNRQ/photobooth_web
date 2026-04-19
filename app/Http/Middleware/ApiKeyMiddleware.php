<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Simple API key guard for kiosk → server communication.
 * Set PHOTOBOOTH_API_KEY in .env and send it as:
 *   Header: X-API-Key: <key>
 */
class ApiKeyMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $key = config('photobooth.api_key');

        // Skip check if no key is configured (dev mode)
        if ($key && $request->header('X-API-Key') !== $key) {
            return response()->json(['success' => false, 'message' => 'Unauthorized.'], 401);
        }

        return $next($request);
    }
}

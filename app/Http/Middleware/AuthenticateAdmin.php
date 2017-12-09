<?php

/**
 * Class AuthenticateAdmin
 */
namespace App\Http\Middleware;

use Closure;

/**
 * Class AuthenticateAdmin
 *
 * Handles Admin API authentication
 */
class AuthenticateAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param         $request
     * @param Closure $next
     * @return mixed
     * @throws \Exception
     */
    public function handle($request, Closure $next)
    {
        if ($request->header('x-auth-key') != config("app.admin_api_key")) {
            return response()->json(['error' => 'Invalid API key'], 401);
        }

        return $next($request);
    }
}

<?php

namespace App\Http\Middleware;

use Closure;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class CustomJWTMiddleware
{

    public function handle($request, Closure $next)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
        } catch (JWTException $e) {
            return response()->json(['error' => 'Unauthorized'], 401);
        } catch (Exception $es) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $next($request);
    }
}

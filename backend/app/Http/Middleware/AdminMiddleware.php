<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 未ログインなら弾く
        if (!$request->user()) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        // is_admin が 1 でなければ弾く
        if ($request->user()->is_admin !== 1) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        return $next($request);
    }
}

<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsurePremium
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user && ($user->is_admin || $user->is_premium)) {
            return $next($request);
        }

        if ($request->expectsJson()) {
            return response()->json(['message' => 'Requiere cuenta premium.'], 403);
        }

        return redirect()->route('premium')->with('premium_required', true);
    }
}

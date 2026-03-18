<?php

namespace App\Http\Middleware;

use App\Models\NavItem;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class CheckNavItemAccess
{
    public function handle(Request $request, Closure $next): Response
    {
        $routeName = $request->route()?->getName();

        if (! $routeName) {
            return $next($request);
        }

        $user = $request->user();

        // Admins bypass all nav-item restrictions.
        if ($user?->is_admin) {
            return $next($request);
        }

        $matchingItem = NavItem::all()->first(
            fn (NavItem $item) => collect(explode('|', $item->match_pattern))
                ->contains(fn (string $pattern) => Str::is(trim($pattern), $routeName))
        );

        if (! $matchingItem) {
            return $next($request);
        }

        if (! $matchingItem->is_enabled) {
            abort(Response::HTTP_FORBIDDEN, 'Esta sección está desactivada.');
        }

        if ($matchingItem->is_premium && ! $user?->is_premium) {
            abort(Response::HTTP_FORBIDDEN, 'Esta sección requiere acceso premium.');
        }

        return $next($request);
    }
}

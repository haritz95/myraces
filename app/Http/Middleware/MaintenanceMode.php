<?php

namespace App\Http\Middleware;

use App\Models\Setting;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class MaintenanceMode
{
    /**
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Setting::get('maintenance_mode') !== '1') {
            return $next($request);
        }

        // Admins bypass maintenance mode
        if ($request->user()?->is_admin) {
            return $next($request);
        }

        // Allow the login page so admins can authenticate
        if ($request->routeIs('login', 'logout')) {
            return $next($request);
        }

        $message = Setting::get('maintenance_message', 'Estamos realizando tareas de mantenimiento. Volvemos pronto.');

        return response()->view('maintenance', ['message' => $message], 503);
    }
}

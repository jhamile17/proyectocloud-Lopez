<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureUserIsAdmin
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();

        // Verifica si el usuario es admin (según tu base de datos)
        $isAdmin = $user && (
            in_array(($user->role ?? null), ['admin', 'superadmin'], true)
            || (bool)($user->is_admin ?? false)
        );

        if (! $isAdmin) {
            // No redirigimos a 'dashboard' para evitar bucles
            return redirect()->route('home');
            // o si prefieres: return abort(403, 'Acceso denegado');
        }

        return $next($request);
    }
}

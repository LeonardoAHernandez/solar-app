<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckAdminRole
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 1. Verificamos si el usuario está logeado y si tiene el rol de admin
        if (Auth::check()) {
            /** @var \App\Models\User $user */
            $user = Auth::user();

            if ($user->isAdmin()) {
                return $next($request);
            }
        }

        // 2. Si no es admin, lo rebotamos al inicio público con un mensaje de error
        return redirect()->route('public.home')->with('error', 'No tienes permisos para acceder a esta sección.');
    }
}

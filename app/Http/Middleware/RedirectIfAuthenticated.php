<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        if (Auth::guard($guards)->check()) {
            if (Auth::user()->role_id == 1) {
                return redirect("/operator/dashboard");
            } else if (Auth::user()->role_id == 2) {
                return redirect("/pertanian/dashboard");
            } else if (Auth::user()->role_id == 3) {
                return redirect("/uptd/dashboard");
            } else if (Auth::user()->role_id == 4) {
                return redirect("/penyuluh/dashboard");
            } else if (Auth::user()->role_id == 5) {
                return redirect("/pangan/dashboard");
            } else if (Auth::user()->role_id == 6) {
                return redirect("/petugas_pasar/dashboard");
            }
        }

        return $next($request);
    }
}

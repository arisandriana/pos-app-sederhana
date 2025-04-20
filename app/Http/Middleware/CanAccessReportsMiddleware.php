<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CanAccessReportsMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Hanya Admin dan Manager yang boleh mengakses laporan
        if (Auth::check() && in_array(Auth::user()->Peran, ['Admin', 'Manager'])) {
            return $next($request);
        }

        return redirect()->route('dashboard')
            ->with('error', 'Anda tidak memiliki izin untuk mengakses laporan!');
    }
}
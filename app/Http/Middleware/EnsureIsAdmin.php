<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureIsAdmin
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Cek apakah user sudah login DAN memiliki role 'admin'
        if ($request->user() && $request->user()->role === 'admin') {
            return $next($request); // Silakan masuk
        }

        // Jika bukan admin, tolak dengan pesan error 403 (Forbidden)
        abort(403, 'Akses Ditolak. Anda bukan Admin.');
    }
}

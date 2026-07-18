<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Dipakai di route lewat ->middleware('role:admin') atau ->middleware('role:pelanggan').
     * $role diambil otomatis dari parameter setelah titik dua.
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (! $request->user() || $request->user()->role !== $role) {
            abort(403, 'Kamu gak punya akses ke halaman ini.');
        }

        return $next($request);
    }
}
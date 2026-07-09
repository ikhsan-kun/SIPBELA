<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RequireEmail
{
    /**
     * Paksa siswa untuk melengkapi email aktif sebelum bisa akses fitur peminjaman.
     * Hanya berlaku jika email masih default (format NIS@siswa.sch.id) atau kosong.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check() && auth()->user()->role === 'siswa') {
            $user = auth()->user();
            $isDefaultEmail = !$user->email || str_ends_with($user->email, '@siswa.sch.id');

            if ($isDefaultEmail) {
                $allowedRoutes = ['password.change', 'password.update', 'password.dismiss_prompt', 'logout'];
                if (!$request->routeIs($allowedRoutes)) {
                    return redirect()->route('password.change', ['from' => 'bengkel'])
                        ->with('error', 'Harap lengkapi email Gmail Anda terlebih dahulu. Email diperlukan untuk menerima notifikasi sistem peminjaman alat.');
                }
            }
        }

        return $next($request);
    }
}

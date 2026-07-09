<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ForceChangePassword
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check() && $request->session()->get('must_change_password') === true) {
            $allowedRoutes = ['password.change', 'password.update', 'logout'];
            if (!$request->routeIs($allowedRoutes)) {
                return redirect()->route('password.change')
                    ->with('error', 'Silakan ganti password default Anda terlebih dahulu demi keamanan akun.');
            }
        }
        return $next($request);
    }
}

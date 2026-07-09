<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Tampilkan form login
     */
    public function showLogin()
    {
        return view('auth.login');
    }

    /**
     * Proses login
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        // Auth::attempt dengan field username (bukan email)
        if (Auth::attempt(['username' => $credentials['username'], 'password' => $credentials['password']], $request->boolean('remember'))) {
            $request->session()->regenerate();
            $request->session()->flash('login_success', true);

            // Cek apakah password masih default (sama dengan username)
            if (\Illuminate\Support\Facades\Hash::check(Auth::user()->username, Auth::user()->password)) {
                $request->session()->put('must_change_password', true);
            } else {
                $request->session()->forget('must_change_password');
            }

            // Redirect by role
            $role = Auth::user()->role;
            if ($role === 'superadmin') {
                return redirect()->intended(route('superadmin.dashboard'))
                    ->with('success', 'Selamat datang Superadmin, ' . Auth::user()->name . '!');
            } elseif ($role === 'admin_bengkel') {
                return redirect()->intended(route('admin.dashboard'))
                    ->with('success', 'Selamat datang Admin Bengkel, ' . Auth::user()->name . '!');
            } elseif ($role === 'admin_perpus') {
                return redirect()->intended(route('perpustakaan.admin.dashboard'))
                    ->with('success', 'Selamat datang Admin Perpus, ' . Auth::user()->name . '!');
            }

            return redirect()->intended(route('portal'))
                ->with('success', 'Selamat datang, ' . Auth::user()->name . '!');
        }

        return back()
            ->withInput($request->only('username'))
            ->withErrors(['username' => 'Username atau password salah.']);
    }

    /**
     * Logout
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Anda berhasil keluar.');
    }
}

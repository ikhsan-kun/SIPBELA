<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class PasswordController extends Controller
{
    public function edit()
    {
        return view('auth.passwords.change');
    }

    public function update(Request $request)
    {
        $user = $request->user();
        $isDefaultEmail = !$user->email || str_ends_with($user->email, '@siswa.sch.id');

        // Validasi dinamis: email wajib jika masih default
        $rules = [
            'current_password' => ['required', 'current_password'],
            'password'         => ['required', 'confirmed', Password::defaults()],
        ];

        // Pesan error dalam bahasa Indonesia
        $messages = [
            'current_password.required'  => 'Password saat ini wajib diisi.',
            'current_password.current_password' => 'Password saat ini tidak sesuai.',
            'password.required'          => 'Password baru wajib diisi.',
            'password.confirmed'         => 'Konfirmasi password tidak cocok.',
            'password.min'               => 'Password baru minimal 8 karakter.',
            'email.required'             => 'Email Gmail wajib diisi agar dapat menerima notifikasi sistem.',
            'email.email'                => 'Format email tidak valid. Contoh: nama@gmail.com',
            'email.unique'               => 'Email ini sudah digunakan oleh akun lain. Gunakan email yang berbeda.',
            'email.max'                  => 'Email terlalu panjang, maksimal 255 karakter.',
        ];

        if ($isDefaultEmail) {
            // Email wajib diisi dan harus unik
            $rules['email'] = ['required', 'email', 'max:255', 'unique:users,email,' . $user->id];
        } else {
            // Email opsional tapi harus valid & unik jika diisi
            $rules['email'] = ['nullable', 'email', 'max:255', 'unique:users,email,' . $user->id];
        }

        $validated = $request->validate($rules, $messages);

        // Update password
        $updateData = [
            'password' => Hash::make($request->password),
        ];

        // Update email jika diisi
        if (!empty($validated['email'])) {
            $updateData['email'] = $validated['email'];
        }

        $user->update($updateData);

        $request->session()->forget('must_change_password');

        return back()->with('success', 'Password' . (!empty($validated['email']) ? ' dan email' : '') . ' berhasil diperbarui.');
    }

    public function dismissPrompt(Request $request)
    {
        $request->session()->forget('must_change_password');
        return response()->json(['success' => true]);
    }
}

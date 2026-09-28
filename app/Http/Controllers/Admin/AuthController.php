<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->validate(['email' => 'required|email', 'password' => 'required|string']);
        if (! Auth::attempt($credentials)) {
            throw ValidationException::withMessages(['email' => 'Email atau password tidak sesuai.']);
        }
        if (! in_array(Auth::user()->role, ['administrator', 'editor', 'sales'], true)) {
            Auth::logout();
            throw ValidationException::withMessages(['email' => 'Akun tidak memiliki akses CMS.']);
        }
        $request->session()->regenerate();

        return redirect()->intended(route('admin.dashboard'));
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    public function updatePassword(Request $request)
    {
        $data = $request->validate(['current_password' => 'required|current_password', 'password' => ['required', 'confirmed', Password::min(12)->mixedCase()->numbers()]]);
        $request->user()->forceFill(['password' => $data['password'], 'remember_token' => null])->save();
        $request->session()->regenerate();

        return back()->with('success', 'Password berhasil diperbarui.');
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            return $this->redirectBasedOnRole(Auth::user());
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return $this->redirectBasedOnRole(Auth::user());
        }

        return back()->with('error', 'Email atau password salah!');
    }

    private function redirectBasedOnRole($user)
    {
        $role = strtolower($user->role); // Normalisasi role ke huruf kecil

        if ($role === 'hcm') {
            return redirect()->route('hcm.dashboard');
        } elseif ($role === 'am') {
            return redirect()->route('am.dashboard');
        } elseif ($role === 'director' || $role === 'direktur') {
            return redirect()->route('director.ranking');
        }

        return redirect('/login')->with('error', 'Role tidak dikenali.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}

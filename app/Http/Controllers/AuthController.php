<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }


    public function showRegister()
    {
        return view('auth.register');
    }



    public function login(Request $request) {
        $validated = $request->validate([
            "email" => "required|email",
            "password" => "required|string|min:8"
        ]);

        if (Auth::attempt($validated)) {
            $request->session()->regenerate();
            return redirect()->route('dashboard.index');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }
    public function logout(Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('show.login');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            "name" => "required|string|max:255",
            "email" => "required|email|unique:users",
            "password" => "required|string|min:1|confirmed"
        ]);

        $user = User::create($validated);
        Auth::login($user);

        return redirect()->route('dashboard.index');
    }
}

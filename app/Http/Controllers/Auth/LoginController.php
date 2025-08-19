<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    // Show login form
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // Handle login POST
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required','email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            // Redirect based on role
            return $this->redirectUser(Auth::user());
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }

    // Logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }

    // Redirect user to dashboard based on role
    protected function redirectUser($user)
    {
        if ((string) $user->role === '5') {
            return redirect()->route('dashboard'); // CEO dashboard
        } elseif ((string) $user->role === '1') {
            return redirect()->route('employee.dashboard'); // Employee dashboard
        } elseif ((string) $user->role === '2') {
            return redirect()->route('callcenter.index'); // Call center dashboard
        } elseif ((string) $user->role === '3') {
            return redirect()->route('marketing.dashboard'); // Marketing dashboard
        } elseif ((string) $user->role === '4') {
            return redirect()->route('shop.dashboard'); // Shop dashboard            
        }
        return redirect()->route('employee.dashboard'); // Other users
    }
}

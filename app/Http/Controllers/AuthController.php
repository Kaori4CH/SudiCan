<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class AuthController extends Controller
{
    public function login(): View
    {
        return view('auth.login');
    }

    public function authenticate(): RedirectResponse
    {
        return redirect()->route('student.dashboard');
    }

    public function register(): View
    {
        return view('auth.register');
    }

    public function store(): RedirectResponse
    {
        return redirect()->route('auth.login');
    }

    public function logout(): RedirectResponse
    {
        return redirect()->route('auth.register');
    }
}

<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureTeacher
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! Auth::check()) {
            return redirect()->route('auth.login')->withErrors([
                'username' => 'Silakan masuk terlebih dahulu untuk mengakses halaman ini.',
            ]);
        }

        $user = Auth::user();

        if ($user->role !== 'teacher' && $user->role !== 'admin') {
            return redirect()->route('student.dashboard')->with('error', 'Akses ditolak. Anda tidak memiliki izin untuk mengakses halaman guru/admin.');
        }

        return $next($request);
    }
}

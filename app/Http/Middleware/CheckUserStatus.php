<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckUserStatus
{
    public function handle(Request $request, Closure $next)
    {
        // Jika user sudah login dan statusnya Non-Aktif
        if (Auth::check() && Auth::user()->status === 'Non-Aktif') {
            Auth::logout(); // Logout paksa
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')->withErrors([
                'email' => 'Akun Anda telah dinonaktifkan oleh Admin.'
            ]);
        }

        return $next($request);
    }
}

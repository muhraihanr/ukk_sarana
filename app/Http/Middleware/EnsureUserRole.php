<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class EnsureUserRole
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        $userRole = Session::get('user_role');

        if (!$userRole || !in_array($userRole, $roles)) {
            // Jika tidak login, redirect ke login
            if (!$userRole) {
                return redirect()->route('login.form', 'siswa');
            }
            
            // Jika role salah, redirect ke dashboard sesuai role
            if ($userRole === 'admin') {
                return redirect()->route('dashboard.admin');
            } else {
                return redirect()->route('dashboard.siswa');
            }
        }

        return $next($request);
    }
}
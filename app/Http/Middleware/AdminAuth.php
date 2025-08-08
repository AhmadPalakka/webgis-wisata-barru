<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminAuth
{
    public function handle(Request $request, Closure $next)
    {
        if (!session('admin_logged_in')) {
            return redirect()->route('admin.login')->withErrors([
                'access' => 'Silakan login sebagai admin terlebih dahulu!'
            ]);
        }
        
        return $next($request);
    }
}
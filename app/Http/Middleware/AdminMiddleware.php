<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if user is logged in as admin
        if (!session('is_admin')) {
            // For AJAX requests, return JSON response
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized access. Please login as admin.',
                    'redirect' => route('admin.login')
                ], 401);
            }
            
            // For regular requests, redirect to admin login
            return redirect()->route('admin.login')
                ->with('error', 'Silakan login terlebih dahulu untuk mengakses admin panel.');
        }

        return $next($request);
    }
}
<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Destination;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    /**
     * Show admin login form
     */
    public function showLoginForm()
    {
        // If already logged in, redirect to dashboard
        if (session('is_admin')) {
            return redirect()->route('admin.dashboard');
        }
        
        return view('admin.login');
    }

    /**
     * Handle admin login
     */
    public function login(Request $request)
    {
        $request->validate([
            'password' => 'required|string'
        ]);

        // Simple password check (you can make this more secure)
        if ($request->password === 'admin123') {
            session(['is_admin' => true, 'admin_user' => 'admin']);
            
            return redirect()->route('admin.dashboard')
                ->with('success', 'Selamat datang di Admin Panel!');
        }

        return back()
            ->withInput()
            ->with('error', 'Password admin salah!');
    }

    /**
     * Handle admin logout
     */
    public function logout(Request $request)
    {
        session()->forget(['is_admin', 'admin_user']);
        session()->flush();
        
        return redirect()->route('admin.login')
            ->with('success', 'Anda telah logout dari admin panel.');
    }

    /**
     * Show admin dashboard
     */
    public function dashboard()
    {
        try {
            // Get statistics
            $totalDestinations = Destination::count();
            $activeDestinations = Destination::where('is_active', true)->count();
            $inactiveDestinations = Destination::where('is_active', false)->count();
            $trashedDestinations = Destination::onlyTrashed()->count();
            
            // Get recent destinations
            $recentDestinations = Destination::latest()
                ->take(5)
                ->get();
            
            // Get category statistics
            $categoryStats = [];
            $categories = Destination::getCategories();
            
            foreach ($categories as $key => $category) {
                $categoryStats[] = [
                    'name' => $category['name'],
                    'icon' => $category['icon'],
                    'color' => $category['color'],
                    'count' => Destination::where('category', $key)->count()
                ];
            }

            return view('admin.dashboard', compact(
                'totalDestinations',
                'activeDestinations', 
                'inactiveDestinations',
                'trashedDestinations',
                'recentDestinations',
                'categoryStats'
            ));
            
        } catch (\Exception $e) {
            \Log::error('Dashboard error: ' . $e->getMessage());
            
            return view('admin.dashboard', [
                'totalDestinations' => 0,
                'activeDestinations' => 0,
                'inactiveDestinations' => 0,
                'trashedDestinations' => 0,
                'recentDestinations' => collect(),
                'categoryStats' => []
            ]);
        }
    }
}
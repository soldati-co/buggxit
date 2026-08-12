<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Dress;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class AdminDashboardController extends Controller
{
    /**
     * Display the admin dashboard with all dynamic data.
     */
    public function index()
    {
        // Safety net: if the stored admin ID is not a valid UUID, force logout and redirect to login
        $adminId = Auth::guard('admin')->id();
        if ($adminId && !Str::isUuid($adminId)) {
            Auth::guard('admin')->logout();
            session()->invalidate();
            session()->regenerateToken();
            return redirect()->route('admin.login')
                ->withErrors('Your session was invalid. Please log in again.');
        }

        // Regular authentication check
        if (!Auth::guard('admin')->check()) {
            return redirect()->route('admin.login');
        }

        // Cache overall stats (5 minutes)
        $stats = Cache::remember('admin_dashboard_stats', 300, function () {
            return [
                'total_dresses'        => Dress::count(),
                'active_dresses'       => Dress::where('status', 'active')->count(),
                'featured_dresses'     => Dress::where('is_featured', true)->count(),
                'draft_dresses'        => Dress::where('status', 'draft')->count(),
                'out_of_stock_dresses' => Dress::where('status', 'out_of_stock')->count(),
            ];
        });

        // Recent dresses with their categories (eager-loaded)
        $recentDresses = Dress::with('categories')
            ->withImageUrls()
            ->latest()
            ->take(5)
            ->get();

        // All active categories, ordered, with dress counts
        $categories = Category::withCount('dresses')
            ->active()
            ->orderBy('sort_order')
            ->get();

        // Calculate estimated revenue from active dresses
        $estimatedRevenue = Dress::where('status', 'active')->sum('price') * 0.3;

        $recentActivity = collect();

        return view('admin.dashboard', compact(
            'stats',
            'recentDresses',
            'categories',
            'estimatedRevenue',
            'recentActivity'
        ));
    }

    /**
     * Get quick stats for AJAX requests.
     */
    public function getQuickStats()
    {
        if (!Auth::guard('admin')->check()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return response()->json([
            'total_dresses'    => Dress::count(),
            'active_dresses'   => Dress::where('status', 'active')->count(),
            'featured_dresses' => Dress::where('is_featured', true)->count(),
        ]);
    }

    /**
     * Clear dashboard cache.
     */
    public function clearCache()
    {
        if (!Auth::guard('admin')->check()) {
            return redirect()->route('admin.login');
        }

        Cache::forget('admin_dashboard_stats');
        
        return back()->with('success', 'Dashboard cache cleared successfully!');
    }
}
<?php

namespace App\Http\Controllers;

use App\Models\Ingredient;
use App\Models\MenuItem;
use App\Models\Order;
use App\Models\StockMovement;
use App\Services\StockService;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function __construct(private StockService $stockService) {}

    public function index()
    {
        //summery
        $totalOrders     = Order::count();
        $deliveredToday  = Order::where('status', 'delivered')
            ->whereDate('delivered_at', today())
            ->count();
        $totalRevenue    = Order::where('status', 'delivered')->sum('total_amount');
        $lowStockCount   = $this->stockService->getLowStockIngredients()->count();

        // Low stock ingredients
        $lowStockItems = $this->stockService->getLowStockIngredients();

        // Recent orders
        $recentOrders = Order::with('orderItems')->latest()->limit(5)->get();

        // Daily ingredient usage for last 7 days
        $dailyUsage = StockMovement::where('type', 'deduction')
            ->where('created_at', '>=', now()->subDays(7))
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(ABS(quantity)) as total_used')
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return view('dashboard.index', compact(
            'totalOrders', 'deliveredToday', 'totalRevenue',
            'lowStockCount', 'lowStockItems', 'recentOrders', 'dailyUsage'
        ));
    }
}

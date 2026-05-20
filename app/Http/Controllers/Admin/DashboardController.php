<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'totalProducts' => Product::count(),
            'activeProducts' => Product::where('is_active', true)->count(),
            'totalOrders' => Order::count(),
            'pendingOrders' => Order::where('status', 'pending')->count(),
            'totalSellers' => User::where('role', 'seller')->count(),
            'totalBuyers' => User::where('role', 'buyer')->count(),
            'totalRevenue' => Order::whereIn('status', ['paid', 'shipped', 'completed'])->sum('total_price'),
            'lowStockCount' => Product::where('stock', '<', 10)->count(),
        ];

        $recentOrders = Order::with(['buyer', 'product'])
            ->latest()
            ->take(5)
            ->get();

        $lowStockProducts = Product::where('stock', '<', 10)
            ->orderBy('stock')
            ->take(5)
            ->get();

        $ordersByStatus = collect(Order::statuses())->map(function ($label, $status) {
            return [
                'status' => $status,
                'label' => $label,
                'count' => Order::where('status', $status)->count(),
            ];
        })->values();

        return view('admin.dashboard', compact('stats', 'recentOrders', 'lowStockProducts', 'ordersByStatus'));
    }
}

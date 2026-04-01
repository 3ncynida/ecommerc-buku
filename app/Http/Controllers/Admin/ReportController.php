<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $dateRange = $request->input('date_range', 'this_month');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $query = Order::query();

        if ($dateRange === 'today') {
            $query->whereDate('created_at', Carbon::today());
        } elseif ($dateRange === '7_days') {
            $query->whereDate('created_at', '>=', Carbon::today()->subDays(7));
        } elseif ($dateRange === 'this_month') {
            $query->whereMonth('created_at', Carbon::now()->month)
                  ->whereYear('created_at', Carbon::now()->year);
        } elseif ($dateRange === 'custom' && $startDate && $endDate) {
            $query->whereBetween('created_at', [
                Carbon::parse($startDate)->startOfDay(), 
                Carbon::parse($endDate)->endOfDay()
            ]);
        }

        $totalRevenue = (clone $query)->where('payment_status', 'success')->sum('total_price');
        $completedOrders = (clone $query)->where('payment_status', 'success')->count();
        $aov = $completedOrders > 0 ? $totalRevenue / $completedOrders : 0;
        $failedOrders = (clone $query)->where('payment_status', 'failed')->count();

        $topBooks = (clone $query)
            ->where('payment_status', 'success')
            ->select('item_id', DB::raw('SUM(quantity) as total_sold'), DB::raw('SUM(total_price) as total_revenue'))
            ->groupBy('item_id')
            ->orderByDesc('total_sold')
            ->take(5)
            ->with('item') 
            ->get();

        return view('admin.reports.index', compact(
            'dateRange', 'startDate', 'endDate',
            'totalRevenue', 'completedOrders', 'aov', 'failedOrders',
            'topBooks'
        ));
    }

    public function generate(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $startDate = Carbon::parse($request->start_date)->startOfDay();
        $endDate = Carbon::parse($request->end_date)->endOfDay();

        $orders = Order::with('item', 'user')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->orderBy('created_at', 'desc')
            ->get();

        $totalRevenue = $orders->sum('total_price');
        $totalOrders = $orders->count();

        return view('admin.reports.show', compact('orders', 'startDate', 'endDate', 'totalRevenue', 'totalOrders'));
    }
}

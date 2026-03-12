<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\StockLog;

class StockLogController extends Controller
{
    public function index()
    {
        $stockLogs = StockLog::with(['item', 'user'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        return view('admin.stock-logs.index', compact('stockLogs'));
    }
}

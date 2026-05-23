<?php

namespace App\Http\Controllers\Branch;

use App\Http\Controllers\Controller;
use App\Models\BranchStockHistory;
use Illuminate\Support\Facades\Auth;

class StockHistoryController extends Controller
{
    public function index()
    {
        $branchId = Auth::user()->branch_id;

        $history = BranchStockHistory::with([
            'product' => fn ($query) => $query->withTrashed(),
            'productVariant' => fn ($query) => $query->withTrashed(),
            'user',
            'order' => fn ($query) => $query->withTrashed(),
        ])
            ->where('branch_id', $branchId)
            ->orderByDesc('created_at')
            ->paginate(20);

        return view('branch.stock-history.index', compact('history'));
    }
}



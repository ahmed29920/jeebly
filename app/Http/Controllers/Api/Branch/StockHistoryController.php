<?php

namespace App\Http\Controllers\Api\Branch;

use App\Http\Controllers\Controller;
use App\Models\BranchStockHistory;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\BranchStockHistoryResource;
class StockHistoryController extends Controller
{
    public function index()
    {
        $branchId = Auth::user()->branch_id;

        $history = BranchStockHistory::with(['product', 'productVariant', 'user', 'order'])
            ->where('branch_id', $branchId)
            ->orderByDesc('created_at')
            ->paginate(20);

        return BranchStockHistoryResource::collection($history);
    }
}



<?php

namespace App\Http\Controllers\Api\Branch;

use App\Exports\TransactionsExport;
use App\Http\Controllers\Controller;
use App\Http\Resources\TransactionResource;
use App\Models\Transaction;
use App\Services\TransactionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class TransactionController extends Controller
{

    protected $transactionService;

    public function __construct(TransactionService $transactionService)
    {
        $this->transactionService = $transactionService;
    }
    public function index()
    {
        $branchId = Auth::user()->branch_id;
        $transactions = $this->transactionService->allForBranch($branchId);
        return TransactionResource::collection($transactions);
    }
    public function show($id)
    {
        $branchId = Auth::user()->branch_id;
        $transaction = $this->transactionService->findByIdForBranch($id, $branchId);
        return TransactionResource::make($transaction);
    }
    public function export()
    {
        return Excel::download(new TransactionsExport, 'transactions.xlsx');
    }
}

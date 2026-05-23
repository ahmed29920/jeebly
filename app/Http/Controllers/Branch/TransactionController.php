<?php

namespace App\Http\Controllers\Branch;

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
        return view('branch.transactions.index',compact('transactions'));
    }
    public function show($uuid)
    {
        $branchId = Auth::user()->branch_id;
        $transaction = $this->transactionService->findByUUIDForBranch($uuid, $branchId);
        abort_if(! $transaction, 404);

        return view('branch.transactions.show', compact('transaction'));
    }
    public function export()
    {
        return Excel::download(new TransactionsExport, 'transactions.xlsx');
    }
}

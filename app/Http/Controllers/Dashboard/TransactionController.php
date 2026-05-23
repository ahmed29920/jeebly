<?php

namespace App\Http\Controllers\Dashboard;

use App\Exports\TransactionsExport;
use App\Http\Controllers\Controller;
use App\Http\Resources\TransactionResource;
use App\Models\Transaction;
use App\Services\TransactionService;
use Illuminate\Http\Request;
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
        $transactions = $this->transactionService->all();
        return view('dashboard.transactions.index',compact('transactions'));
    }
    public function show($uuid)
    {
        $transaction = $this->transactionService->findByUUID($uuid);
        abort_if(! $transaction, 404);

        return view('dashboard.transactions.show', compact('transaction'));
    }
    public function export()
    {
        return Excel::download(new TransactionsExport, 'transactions.xlsx');
    }
}

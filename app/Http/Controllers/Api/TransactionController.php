<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\TransactionResource;
use App\Models\Transaction;
use App\Services\TransactionService;
use Illuminate\Http\Request;

class TransactionController extends Controller
{

    protected $transactionService;

    public function __construct(TransactionService $transactionService)
    {
        $this->transactionService = $transactionService;
    }
    public function index()
    {
        $limit = request()->input('limit', 15);
        $userId = auth()->user()->id;
        $orders = $this->transactionService->getUserTransactions($userId, $limit);
        return TransactionResource::collection($orders);
    }
    public function show($id)
    {
        $userId = auth()->user()->id;
        $orders = $this->transactionService->getUserTransactionById($userId, $id);
        return new TransactionResource($orders);
    }
    public function pay($id)
    {
        $orders = $this->transactionService->pay($id);
        return new TransactionResource($orders);
    }
}

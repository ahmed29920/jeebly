<?php

namespace App\Exports;

use App\Models\Transaction;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class TransactionsExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Transaction::with([
            'user',
            'order' => fn ($query) => $query->withTrashed(),
        ])
            ->get()
            ->map(function ($transaction) {
                return [
                    'id'                => $transaction->id,
                    'user_name'         => $transaction->user?->name ?? '',
                    'user_email'        => $transaction->user?->email ?? '',
                    'order_uuid'        => $transaction->order?->uuid ?? '',
                    'payment_method'    => $transaction->payment_method,
                    'amount'            => $transaction->amount,
                    'currency'          => $transaction->currency,
                    'status'            => $transaction->status,
                    'transaction_id'    => $transaction->transaction_id,
                    'reference_number'  => $transaction->reference_number,
                    'created_at'        => $transaction->created_at->toDateTimeString(),
                ];
            });
    }

    public function headings(): array
    {
        return [
            'ID',
            'User Name',
            'User Email',
            'Order UUID',
            'Payment Method',
            'Amount',
            'Currency',
            'Status',
            'Transaction ID',
            'Reference Number',
            'Created At',
        ];
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DeliveryWalletHistory extends Model
{
    protected $table = 'delivery_wallet_history';

    protected $fillable = [
        'delivery_id',
        'order_id',
        'amount',
        'type',
        'wallet_before',
        'wallet_after',
        'notes',
    ];

    protected $casts = [
        'amount'        => 'decimal:2',
        'wallet_before' => 'decimal:2',
        'wallet_after'  => 'decimal:2',
    ];

    public function delivery(): BelongsTo
    {
        return $this->belongsTo(Delivery::class);
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}

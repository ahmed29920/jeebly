<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DeliveryWalletRequest extends Model
{
    protected $fillable = [
        'delivery_id',
        'type',
        'amount',
        'order_id',
        'status',
        'notes',
        'admin_notes',
        'processed_by',
        'processed_at',
    ];

    protected $casts = [
        'amount'       => 'decimal:2',
        'processed_at' => 'datetime',
    ];

    public static function isCodPaymentMethod(?string $method): bool
    {
        if (! $method) {
            return false;
        }

        $normalized = strtolower(str_replace(['-', ' '], '_', $method));

        return in_array($normalized, ['cod', 'cash_on_delivery', 'cash', 'cashondelivery'], true);
    }

    public function delivery(): BelongsTo
    {
        return $this->belongsTo(Delivery::class);
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function processedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'processed_by');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }
}

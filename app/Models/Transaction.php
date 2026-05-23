<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
class Transaction extends Model
{
    protected $fillable = [
        'user_id',
        'uuid',
        'order_id',
        'payment_method',
        'amount',
        'currency',
        'status',
        'transaction_id',
        'reference_number',
        'raw_response',
    ];

    protected static function booted()
    {
        static::creating(function ($transaction) {
            $transaction->uuid = Str::uuid();
        });
    }

    public function user(){
        return $this->belongsTo(User::class);
    }
    public function order(){
        return $this->belongsTo(Order::class);
    }

}

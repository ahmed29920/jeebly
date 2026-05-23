<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookingList extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'user_id',
        'quantity',
        'expected_at',
        'notified',
        'status',
    ];
    protected $casts = [
        'expected_at' => 'datetime',
        'notified' => 'boolean',
        'status' => 'string',
    ];

    public function scopeWithExistingProduct(Builder $query): Builder
    {
        return $query->whereHas('product');
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

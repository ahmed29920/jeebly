<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Order extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'status',
        'total',
        'shipping_cost',
        'coupon_id',
        'points_discount_value',
        'coupon_discount_value',
        'offer_discount_value',
        'offer_id',
        'final_total',
        'payment_status',
        'payment_method',
        'shipping_address_id',
        'billing_address_id',
        'branch_id',
        'note',
        'delivery_id',
        'delivery_assigned_at',
	'route',
    ];

    protected $casts = [
        'total'          => 'decimal:2',
        'coupon_discount_value' => 'decimal:2',
        'delivery_assigned_at' => 'datetime',
        'offer_discount_value' => 'decimal:2',
        'final_total'    => 'decimal:2',
        'shipping_cost'  => 'decimal:2',
        'branch_id'      => 'integer',
	'route'         => 'json',
    ];
    protected static function booted()
    {
        static::creating(function ($order) {
            $order->uuid = Str::uuid();
        });
        static::created(function ($order) {
            $order->transaction()->create([
                'user_id'   => $order->user_id,
                'amount'    => $order->final_total,
                'currency'  => currency_code()
            ]);
        });
    }

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function coupon()
    {
        return $this->belongsTo(Coupon::class);
    }
    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function offer()
    {
        return $this->belongsTo(Offer::class);
    }

    public function shippingAddress()
    {
        return $this->belongsTo(Address::class, 'shipping_address_id');
    }

    public function billingAddress()
    {
        return $this->belongsTo(Address::class, 'billing_address_id');
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function comments()
    {
        return $this->hasMany(OrderComment::class);
    }

    public function transaction()
    {
        return $this->hasOne(Transaction::class);
    }
    public function delivery()
    {
        return $this->belongsTo(Delivery::class)->withDefault([
            'name' => 'No Delivery Assigned',
            'email' => 'No Delivery Assigned',
            'phone' => 'No Delivery Assigned',
        ]);
    }
    /*
    |--------------------------------------------------------------------------
    | Accessors
    |--------------------------------------------------------------------------
    */
    public function getHasDiscountAttribute(): bool
    {
        return !is_null($this->coupon_id) && $this->discount > 0;
    }
}

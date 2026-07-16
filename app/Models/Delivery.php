<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Delivery extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'is_online',
        'plate_number',
        'vehicle_name',
        'vehicle_type',
        'vehicle_color',
        'wallet',
        'documents',
        'branch_id',
    ];

    protected $casts = [
        'documents' => 'array',
        'wallet' => 'decimal:2',
        'is_online' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class)->withDefault([
            'name' => 'All Branches',
            'address' => 'All Branches',
            'phone' => 'All Branches',
            'latitude' => 0,
            'longitude' => 0,
            'is_active' => true,
        ]);
    }

    public function scopeOnline($query)
    {
        return $query->where('is_online', true);
    }

    public function scopeOffline($query)
    {
        return $query->where('is_online', false);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    // scope to get the orders that assigned to this delivery but not completed
    public function scopeNotDelivered($query){
        return $query->whereHas('orders', function($query){
            $query->whereNotNull('delivery_id')->where('delivery_id', $this->id)->where('status', '!=', 'completed');
        });
    }
    // scope to get the orders that assigned to this delivery and completed
    public function scopeDelivered($query){
        return $query->whereHas('orders', function($query){
            $query->whereNotNull('delivery_id')->where('delivery_id', $this->id)->where('status', 'completed');
        });
    }

    public function currentOrder()
    {
        return $this->hasOne(Order::class)->whereNotNull('delivery_id')->where('delivery_id', $this->id)->where('status', 'out_for_delivery');
    }

    public function walletHistory()
    {
        return $this->hasMany(DeliveryWalletHistory::class);
    }

    public function walletRequests()
    {
        return $this->hasMany(DeliveryWalletRequest::class);
    }

    /**
     * Zones that this delivery can cover.
     */
    public function zones(): BelongsToMany
    {
        return $this->belongsToMany(Zone::class, 'delivery_zone')
            ->withTimestamps();
    }

}

<?php

namespace App\Models;

use App\Models\Concerns\HasInternationalPhoneDisplay;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use HasFactory, HasInternationalPhoneDisplay;

    protected $fillable = [
        'user_id',
        'name',
        'phone',
        'address',
        'city',
        'state',
        'country',
        'postal_code',
        'latitude',
        'longitude',
        'status'
    ];

    protected $casts = [
        'status' => 'boolean'    
    ];
    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

<?php

namespace App\Models;

use App\Models\Concerns\HasInternationalPhoneDisplay;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;
class Branch extends Model
{
    use HasTranslations, HasInternationalPhoneDisplay;
    protected $fillable = [
        'name',
        'slug',
        'address',
        'phone',
        'latitude',
        'longitude',
        'is_active',
        'is_online',
    ];
    protected $casts = [
        'name' => 'array',
        'address' => 'array',
        'is_active' => 'boolean',
        'is_online' => 'boolean',
    ];
    public $translatable = [
        'name',
        'address',
    ];
    
}

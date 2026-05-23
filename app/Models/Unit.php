<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;
class Unit extends Model
{
    use HasTranslations;
    protected $fillable = [
        'name',
        'code',
        'is_active',
    ];
    public $translatable = [
        'name',
    ];
    protected $casts = [
        'name' => 'array',
        'code' => 'array',
        'is_active' => 'boolean',
    ];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Slider extends Model
{
    protected $fillable = [
        'image',
        'is_active',
    ];
    protected $casts = [
        'is_active' => 'boolean',
    ];
    protected $appends = ['image_url'];
    public function getImageUrlAttribute()
    {
        return $this->image ? asset('storage/' . $this->image) : null;
    }
}

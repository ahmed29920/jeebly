<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;

class VariantOption extends Model
{
    use SoftDeletes, HasTranslations;

    protected $fillable = [
        'variant_id',
        'name',
        'code',
    ];

    public $translatable = ['name'];
    public function variant()
    {
        return $this->belongsTo(Variant::class);
    }
}

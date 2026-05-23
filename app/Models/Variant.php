<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;

class Variant extends Model
{
    use SoftDeletes, HasTranslations;

    protected $fillable = [
        'name',
        'is_active',
        'is_required',
        'type',
    ];

    public $translatable = ['name'];

    public function options()
    {
        return $this->hasMany(VariantOption::class);
    }
}

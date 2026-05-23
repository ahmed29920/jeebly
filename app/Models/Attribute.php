<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;

class Attribute extends Model
{
    use HasTranslations;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'code',
        'name',
        'type',
        'is_required',
        'is_filterable',
        'validation',
        'is_active'
    ];

    /**
     * The attributes that are translatable.
     *
     * @var array
     */
    public $translatable = ['name'];


    /**
     * default values for attributes
     *
     * @var array<string, mixed>
     */
    protected $attributes = [
        'is_required'   => false,
        'is_filterable' => false,
        'is_active' => false,
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_required'   => 'boolean',
        'is_filterable' => 'boolean',
        'is_active' => 'boolean',
        'name'          => 'array', // json
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */
    public function options()
    {
        return $this->hasMany(AttributeOption::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Accessors
    |--------------------------------------------------------------------------
    */
    public function getLabelAttribute(): string
    {
        return $this->getTranslation('name', app()->getLocale());
    }
}

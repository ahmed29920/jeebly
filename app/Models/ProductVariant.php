<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Concerns\SuffixesUniqueFieldsOnSoftDelete;

class ProductVariant extends Model
{
    use HasFactory, SoftDeletes, HasTranslations, SuffixesUniqueFieldsOnSoftDelete;

    protected $fillable = [
        'name',
        'product_id',
        'slug',
        'sku',
        'stock',
        'price',
        'is_active',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($variation) {
            // Check if product type is variable (not simple)
            if ($variation->product->type !== 'variable') {
                throw new \Exception("Cannot add variations to a simple product.");
            }
        });
    }

    public $translatable = ['name'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    public function values()
    {
        return $this->hasMany(ProductVariantValue::class);
    }
    public function options()
    {
        return $this->hasMany(VariantOption::class);
    }

    public function images()
    {
        return $this->morphMany(ProductImage::class, 'imageable');
    }

    public function branchVariantStocks()
    {
        return $this->hasMany(BranchVariantStock::class);
    }
}

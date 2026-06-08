<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;
use App\Factories\ProductPriceStockFactory;
use App\Models\Concerns\SuffixesUniqueFieldsOnSoftDelete;

class Product extends Model
{
    use HasFactory, SoftDeletes, HasTranslations, SuffixesUniqueFieldsOnSoftDelete;

    protected $fillable = [
        'type',
        'stock',
        'price',
        'name',
        'slug',
        'description',
        'short_description',
        'sku',
        'discount',
        'discount_type',
        'max_order_quantity',
        'manage_stock',
        'is_active',
        'is_featured',
        'is_new',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'is_bookable',
        'unit_id',
    ];

    public $translatable = [
        'name',
        'description',
        'short_description',
        'meta_title',
        'meta_description',
        'meta_keywords',
    ];

    protected $casts = [
        'discount'      => 'decimal:2',
        'manage_stock'  => 'boolean',
        'is_active'     => 'boolean',
        'is_featured'   => 'boolean',
        'is_new'        => 'boolean',
        'type'          => 'string',
        'stock'         => 'integer',
        'price'         => 'decimal:2',
        'is_bookable'   => 'boolean',
        'unit_id'       => 'integer',
    ];

    protected static function boot()
    {
        parent::boot();

        // Note: Type conversion from variable to simple is handled in ProductService
        // which deletes variants before updating the type
    }

    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    */
    public function scopeActive(Builder $builder)
    {
        return $builder->where('is_active', true);
    }
    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'category_product');
    }


    public function attributes()
    {
        return $this->belongsToMany(Attribute::class, 'product_attribute_values')->withPivot('attribute_option_id', 'value')->withTimestamps();
    }

    public function attributeOptions()
    {
        return $this->belongsToMany(AttributeOption::class, 'product_attribute_values')->withPivot('attribute_id', 'value')->withTimestamps();
    }

    public function images()
    {
        return $this->morphMany(ProductImage::class, 'imageable');
    }
    public function relations()
    {
        return $this->hasMany(ProductRelation::class, 'product_id');
    }

    public function relatedProducts()
    {
        return $this->relations()->where('type', 'related')->with('relatedProduct');
    }

    public function crossSellProducts()
    {
        return $this->relations()->where('type', 'cross_sell')->with('relatedProduct');
    }
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function approvedReviews()
    {
        return $this->reviews()->where('status', 'approved');
    }

    public function averageRating()
    {
        return $this->approvedReviews()->avg('rating');
    }

    public function favoritedBy()
    {
        return $this->belongsToMany(User::class, 'favorites')->withTimestamps();
    }
    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }
    public function variantValues()
    {
        return $this->hasManyThrough(ProductVariantValue::class, ProductVariant::class);
    }
    public function branchProductStocks()
    {
        return $this->hasMany(BranchProductStock::class);
    }
    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }
    /*
    |--------------------------------------------------------------------------
    | Accessors & Mutators
    |--------------------------------------------------------------------------
    */
    public function getShortDescriptionTextAttribute()
    {
        return $this->getTranslation('short_description', app()->getLocale());
    }

    public function getDescriptionTextAttribute()
    {
        return $this->getTranslation('description', app()->getLocale());
    }

    public function manager()
    {
        return ProductPriceStockFactory::make($this);
    }
}

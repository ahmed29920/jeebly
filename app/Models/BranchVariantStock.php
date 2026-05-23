<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BranchVariantStock extends Model
{
    use SoftDeletes;

    protected $table = 'branch_variant_stocks';

    protected $fillable = [
        'branch_id',
        'product_variant_id',
        'quantity',
    ];

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function productVariant()
    {
        return $this->belongsTo(ProductVariant::class);
    }
}

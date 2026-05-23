<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BranchProductStock extends Model
{
    use SoftDeletes;

    protected $table = 'branch_product_stocks';

    protected $fillable = [
        'branch_id',
        'product_id',
        'quantity',
    ];


    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}

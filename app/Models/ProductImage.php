<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductImage extends Model
{
    protected $fillable = ['imageable_id', 'imageable_type', 'path'];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */
    public function imageable()
    {
        return $this->morphTo();
    }
    /*
    |--------------------------------------------------------------------------
    | Accessors
    |--------------------------------------------------------------------------
    */

    public function getImagePathAttribute(){
        return asset('storage/'.$this->path);
    }
}

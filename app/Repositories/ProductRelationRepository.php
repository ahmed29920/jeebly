<?php

namespace App\Repositories;

use App\Models\ProductRelation;

class ProductRelationRepository
{
    protected $model;
    public function __construct(ProductRelation $model)
    {
        $this->model = $model;
    }
    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function deleteByProductId($productId)
    {
        return $this->model->where('product_id', $productId)->delete();
    }

    public function getByProductId($productId)
    {
        return $this->model->where('product_id', $productId)->get();
    }
}

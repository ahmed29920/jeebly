<?php

namespace App\Repositories;

use App\Models\Category;

class CategoryRepository
{
    protected $model;

    public function __construct(Category $category)
    {
        $this->model = $category;
    }

    public function all($limit)
    {
        // dd($limit);
        $query = $this->model->with('children')->orderBy('sort_order');

        
        if (request()->has('category_id')){
            $query->where('parent_id', request()->category_id);
        }
        
        if ($limit == -1) {
            return $query->get();
        }
        

        return $query->paginate($limit);
    }

    public function allForBranch($branchId)
    {
        return $this->model
            ->with('children')
            ->whereHas('products.branchProductStocks', function ($query) use ($branchId) {
                $query->where('branch_id', $branchId);
            })
            ->orderBy('sort_order')
            ->get();
    }

    public function find(int $id)
    {
        return $this->model->with('children','products.unit')->findOrFail($id);
    }

    public function findBySlug(string $slug)
    {
        return $this->model->with('children')->where('slug', $slug)->firstOrFail();
    }

    public function findForBranchBySlug(string $slug, int $branchId)
    {
        return $this->model->with('children')->where('slug', $slug)->whereHas('products.branchProductStocks', function ($query) use ($branchId) {
            $query->where('branch_id', $branchId);
        })->firstOrFail();
    }


    public function create(array $data): Category
    {
        return $this->model->create($data);
    }


    public function update(Category $category, array $data): Category
    {
        $category->update($data);
        return $category;
    }


    public function delete(Category $category): bool
    {
        return $category->delete();
    }


    public function bulkAction(array $ids, string $action)
    {
        switch ($action) {
            case 'delete':
                return $this->model->whereIn('id', $ids)->delete();

            case 'activate':
                return $this->model->whereIn('id', $ids)->update(['is_active' => 1]);

            case 'deactivate':
                return $this->model->whereIn('id', $ids)->update(['is_active' => 0]);

            default:
                return false;
        }
    }
}

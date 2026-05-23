<?php

namespace App\Repositories;

use App\Models\Attribute;

class AttributeRepository
{
    protected $model;

    public function __construct(Attribute $attribute)
    {
        $this->model = $attribute;
    }
    
    public function all()
    {
        return $this->model->with('options')->latest()->get();
    }

    public function find($id)
    {
        return $this->model->with('options')->findOrFail($id);
    }

    public function create(array $data)
    {
        $attribute = $this->model->create($data);
        return $attribute;
    }

    public function update(Attribute $attribute, array $data)
    {
        $attribute->update($data);
        return $attribute;
    }

    public function delete(Attribute $attribute)
    {
        return $attribute->delete();
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

            case 'required':
                return $this->model->whereIn('id', $ids)->update(['is_required' => 1]);

            case 'not_required':
                return $this->model->whereIn('id', $ids)->update(['is_required' => 0]);

            case 'filterable':
                return $this->model->whereIn('id', $ids)->update(['is_filterable' => 1]);

            case 'not_filterable':
                return $this->model->whereIn('id', $ids)->update(['is_filterable' => 0]);

            default:
                return false;
        }
    }
}

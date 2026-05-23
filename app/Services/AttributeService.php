<?php

namespace App\Services;

use App\Models\Attribute;
use App\Repositories\AttributeRepository;

class AttributeService
{
    protected $repo;

    public function __construct(AttributeRepository $repo)
    {
        $this->repo = $repo;
    }

    public function all()
    {
        return $this->repo->all();
    }

    public function find($id)
    {
        return $this->repo->find($id);
    }

    public function store(array $data)
    {
        $attribute = $this->repo->create($data);

        if (in_array($data['type'], ['select', 'multiselect']) && !empty($data['options'])) {
            $attribute->options()->createMany($data['options']);
        }

        return $attribute;
    }
    public function update(Attribute $attribute, array $data)
    {
        $this->repo->update($attribute, $data);


        if (in_array($data['type'], ['select', 'multiselect'])) {
            $attribute->options()->delete();
            if (!empty($data['options'])) {
                $attribute->options()->createMany($data['options']);
            }
        }

        return $attribute->refresh();
    }

    public function delete(Attribute $attribute)
    {
        return $this->repo->delete($attribute);
    }

    public function bulkAction(array $ids, string $action)
    {
        return $this->repo->bulkAction($ids, $action);
    }

}

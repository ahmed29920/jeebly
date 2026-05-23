<?php

namespace App\Services;

use App\Repositories\CategoryRepository;
use App\Models\Category;
use Illuminate\Http\UploadedFile;

class CategoryService
{
    protected $categoryRepo;

    public function __construct(CategoryRepository $categoryRepo)
    {
        $this->categoryRepo = $categoryRepo;
    }

    public function all($limit)
    {
        return $this->categoryRepo->all($limit);
    }

    public function allForBranch($branchId)
    {
        return $this->categoryRepo->allForBranch($branchId);
    }

    public function find(int $id)
    {
        return $this->categoryRepo->find($id);
    }

    public function findBySlug($slug)
    {
        return $this->categoryRepo->findBySlug($slug);
    }
    public function findForBranchBySlug($slug , $branchId)
    {
        return $this->categoryRepo->findForBranchBySlug($slug, $branchId);
    }

    public function store(array $data): Category
    {
        $data = $this->handleImage($data);
        return $this->categoryRepo->create($data);
    }

    public function update(Category $category, array $data): Category
    {
        $data = $this->handleImage($data);
        return $this->categoryRepo->update($category, $data);
    }

    public function delete(Category $category): bool
    {
        return $this->categoryRepo->delete($category);
    }


    protected function handleImage(array $data): array
    {
        if (!empty($data['image']) && $data['image'] instanceof UploadedFile) {
            $data['image'] = $data['image']->store('categories', 'public');
        }

        return $data;
    }

    public function getCategoryTree($excludeId = null)
    {
        $query = $this->categoryRepo->all(-1)->where('is_active', 1);

        if ($excludeId) {
            $query = $query->where('id', '!=', $excludeId);
        }

        $categories = $query->toArray();

        return $this->buildTree($categories);
    }

    private function buildTree(array $elements, $parentId = null)
    {
        $branch = [];

        foreach ($elements as $element) {
            if ($element['parent_id'] == $parentId) {
                $children = $this->buildTree($elements, $element['id']);
                if ($children) {
                    $element['children'] = $children;
                }
                $branch[] = $element;
            }
        }

        return $branch;
    }


    public function bulkAction(array $ids, string $action)
    {
        return $this->categoryRepo->bulkAction($ids, $action);
    }
}

<?php

namespace App\Repositories;

use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class ProductRepository
{
    protected $model;
    public function __construct(Product $model)
    {
        $this->model = $model;
    }
    public function all($branchId = null)
    {
        $query = $this->model->with([
            'categories',
            'images',
            'unit',
            'branchProductStocks' => function ($q) use ($branchId) {
                if ($branchId) {
                    $q->where('branch_id', $branchId);
                }
            },
            'variants.branchVariantStocks' => function ($q) use ($branchId) {
                if ($branchId) {
                    $q->where('branch_id', $branchId);
                }
            },
        ]);

        if ($branchId) {
            // Only products that have stock records in this branch (simple or variant)
            $query->where(function ($q) use ($branchId) {
                $q->whereHas('branchProductStocks', function ($sq) use ($branchId) {
                    $sq->where('branch_id', $branchId);
                })->orWhereHas('variants.branchVariantStocks', function ($sq) use ($branchId) {
                    $sq->where('branch_id', $branchId);
                });
            });
        }
        
        if (request()->has('category_id')){
            $query->whereHas('categories', function ($q) {
                $q->where('category_id', request()->category_id);
            });
        }

        return $query->get();
    }

    // (get only active products) used for shop
    public function active($limit, $filters)
    {
        $query = Product::query()->active()->with(['categories', 'images']);

        if (!empty($filters['category_id'])) {
            $query->whereHas('categories', function ($q) use ($filters) {
                $q->where('category_id', $filters['category_id']);
            });
        }


        if (isset($filters['featured']) && $filters['featured'] === true) {
            $query->where('is_featured', true);
        }

        if (isset($filters['new']) && $filters['new'] === true) {
            $query->where('is_new', $filters['new']);
        }

        if (!empty($filters['price_min'])) {
            // For variable products, check variant prices
            $query->where(function ($q) use ($filters) {
                $q->where(function ($sq) use ($filters) {
                    $sq->where('type', 'simple')
                        ->where('price', '>=', $filters['price_min']);
                })->orWhereHas('variants', function ($variantQuery) use ($filters) {
                    $variantQuery->where('price', '>=', $filters['price_min']);
                });
            });
        }

        if (!empty($filters['price_max'])) {
            // For variable products, check variant prices
            $query->where(function ($q) use ($filters) {
                $q->where(function ($sq) use ($filters) {
                    $sq->where('type', 'simple')
                        ->where('price', '<=', $filters['price_max']);
                })->orWhereHas('variants', function ($variantQuery) use ($filters) {
                    $variantQuery->where('price', '<=', $filters['price_max']);
                });
            });
        }

        if (!empty($filters['attribute_options'])) {
            $optionIds = is_array($filters['attribute_options'])
                ? $filters['attribute_options']
                : [$filters['attribute_options']];

            $query->whereHas('attributeOptions', function ($q) use ($optionIds) {
                $q->whereIn('attribute_option_id', $optionIds);
            });
        }

        if (isset($filters['has_discount']) && $filters['has_discount'] === true) {
            $query->where('discount', '>', 0)->orWhereHas('variants', function ($q) {
                $q->where('discount', '>', 0);
            });
        }

        if (isset($filters['sort'])) {
            $sort = $filters['sort'];
            switch ($sort) {
                case 'newest':
                    $query->orderBy('created_at', 'DESC');
                    break;
                case 'oldest':
                    $query->orderBy('created_at', 'ASC');
                    break;
                case 'expensive':
                    // For variable products, use min variant price
                    $query->orderByRaw('CASE WHEN type = "variable" THEN (SELECT MIN(price) FROM product_variants WHERE product_id = products.id) ELSE price END DESC');
                    break;
                case 'cheap':
                    // For variable products, use min variant price
                    $query->orderByRaw('CASE WHEN type = "variable" THEN (SELECT MIN(price) FROM product_variants WHERE product_id = products.id) ELSE price END ASC');
                    break;
                case 'a_to_z':
                    $query->orderBy('name', 'ASC');
                    break;
                case 'z_to_a':
                    $query->orderBy('name', 'DESC');
                    break;
            }
        }

        // Load variants for variable products
        $query->with(['variants' => function ($q) {
            $q->where('is_active', true)
                ->with(['images', 'values.variantOption.variant']);
        }]);

        if (Auth::check()) {
            $query->withExists([
                'favoritedBy as is_favorite' => function ($q) {
                    $q->where('user_id', auth()->id());
                }
            ]);
        }
        $query->with('unit');

        return $query->paginate($limit);
    }


    public function find($id)
    {
        return $this->model->with(array_merge([
            'categories',
            'attributes.options',
            'images',
            'unit',
            'variants.images',
            'variants.values.variantOption.variant',
            'approvedReviews',
        ], $this->relatedProductRelations()))->findOrFail($id);
    }

    public function findActive($id)
    {
        return $this->model->active()->with(array_merge([
            'categories',
            'attributes.options',
            'images',
            'unit',
            'variants' => function ($q) {
                $q->where('is_active', true)
                    ->with(['images', 'values.variantOption.variant']);
            },
            'approvedReviews',
        ], $this->activeRelatedProductRelations()))->findOrFail($id);
    }

    public function findForBranchBySlug(string $slug, int $branchId)
    {
        return $this->model
            ->with([
                'categories',
                'images',
                'unit',
                'branchProductStocks' => function ($q) use ($branchId) {
                    $q->where('branch_id', $branchId);
                },
                'variants.images',
                'variants.values.variantOption.variant',
                'variants.branchVariantStocks' => function ($q) use ($branchId) {
                    $q->where('branch_id', $branchId);
                },
            ])
            ->where(function ($q) use ($branchId) {
                $q->whereHas('branchProductStocks', function ($sq) use ($branchId) {
                    $sq->where('branch_id', $branchId);
                })->orWhereHas('variants.branchVariantStocks', function ($sq) use ($branchId) {
                    $sq->where('branch_id', $branchId);
                });
            })
            ->where('slug', $slug)->firstOrFail();
    }

    public function findForBranchById(int $id, int $branchId)
    {
        return $this->model
            ->with([
                'categories',
                'images',
                'unit',
                'branchProductStocks' => function ($q) use ($branchId) {
                    $q->where('branch_id', $branchId);
                },
                'variants.images',
                'variants.values.variantOption.variant',
                'variants.branchVariantStocks' => function ($q) use ($branchId) {
                    $q->where('branch_id', $branchId);
                },
            ])
            ->where(function ($q) use ($branchId) {
                $q->whereHas('branchProductStocks', function ($sq) use ($branchId) {
                    $sq->where('branch_id', $branchId);
                })->orWhereHas('variants.branchVariantStocks', function ($sq) use ($branchId) {
                    $sq->where('branch_id', $branchId);
                });
            })
            ->where('id', $id)->firstOrFail();
    }

    public function findBySlug(string $slug)
    {
        return $this->model->with(array_merge([
            'categories',
            'attributes.options',
            'images',
            'variants.values.variantOption.variant',
            'variants.images',
            'approvedReviews',
        ], $this->relatedProductRelations()))->where('slug', $slug)->firstOrFail();
    }


    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function update(Product $product, array $data)
    {
        $product->update($data);
        return $product;
    }

    public function delete(Product $product)
    {
        return $product->delete();
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
    public function search($q, bool $activeOnly = false)
    {
        $query = Product::query()->with(['images', 'unit']);

        if ($activeOnly) {
            $query->active();
        }

        return $query->where(function ($query) use ($q) {
                $query->whereRaw("LOWER(JSON_UNQUOTE(JSON_EXTRACT(name, '$.en'))) LIKE ?", ['%' . strtolower($q) . '%'])
                    ->orWhereRaw("LOWER(JSON_UNQUOTE(JSON_EXTRACT(name, '$.ar'))) LIKE ?", ['%' . strtolower($q) . '%'])
                    ->orWhere('sku', 'like', "%{$q}%");
            })->limit(10)->get(['id', 'name', 'sku']);
    }

    protected function activeRelatedProductRelations(): array
    {
        return [
            'relatedProducts' => fn ($query) => $query
                ->withExistingRelatedProduct()
                ->whereHas('relatedProduct', fn ($q) => $q->where('is_active', true))
                ->with(['relatedProduct' => fn ($q) => $q->where('is_active', true)->with('images')]),
            'crossSellProducts' => fn ($query) => $query
                ->withExistingRelatedProduct()
                ->whereHas('relatedProduct', fn ($q) => $q->where('is_active', true))
                ->with(['relatedProduct' => fn ($q) => $q->where('is_active', true)->with('images')]),
        ];
    }

    protected function relatedProductRelations(): array
    {
        return [
            'relatedProducts' => fn ($query) => $query
                ->withExistingRelatedProduct()
                ->with(['relatedProduct.images']),
            'crossSellProducts' => fn ($query) => $query
                ->withExistingRelatedProduct()
                ->with(['relatedProduct.images']),
        ];
    }
}

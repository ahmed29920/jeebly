<?php

namespace App\Services;

use App\Imports\ProductsImport;
use App\Mail\ProductAvailableMail;
use App\Models\BranchProductStock;
use App\Models\BranchStockHistory;
use App\Models\BranchVariantStock;
use App\Models\Product;
use App\Models\VariantOption;
use App\Repositories\BookingListRepository;
use App\Repositories\ProductRelationRepository;
use App\Repositories\ProductRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class ProductService
{
    protected $productRepo;

    protected $relationRepo;

    protected $bookingListRepo;

    public function __construct(ProductRepository $productRepo, ProductRelationRepository $relationRepo, BookingListRepository $bookingListRepo)
    {
        $this->productRepo = $productRepo;
        $this->relationRepo = $relationRepo;
        $this->bookingListRepo = $bookingListRepo;
    }

    public function all($branchId = null)
    {
        return $this->productRepo->all($branchId);
    }

    public function active($limit)
    {
        $filters = request()->only([
            'category_id',
            'featured',
            'new',
            'price_min',
            'price_max',
            'attribute_options',
            'sort',
        ]);

        // Normalize values
        $filters = array_map(function ($value) {
            if (is_string($value)) {
                if ($value === 'true') {
                    return true;
                }
                if ($value === 'false') {
                    return false;
                }
                if ($value === 'null' || $value === '') {
                    return null;
                }
            }

            return $value;
        }, $filters);

        return $this->productRepo->active($limit, $filters);
    }

    public function find($id)
    {
        return $this->productRepo->find($id);
    }

    public function findActive($id)
    {
        return $this->productRepo->findActive($id);
    }

    public function findForBranchBySlug(string $slug, int $branchId)
    {
        return $this->productRepo->findForBranchBySlug($slug, $branchId);
    }

    public function findForBranchById(int $id, int $branchId)
    {
        return $this->productRepo->findForBranchById($id, $branchId);
    }

    public function findBySlug($slug)
    {
        return $this->productRepo->findBySlug($slug);
    }

    public function store(array $data)
    {
        $data['is_new'] = isset($data['is_new']) ? $data['is_new'] : false;
        $data['is_active'] = isset($data['is_active']) ? $data['is_active'] : false;
        $data['is_featured'] = isset($data['is_featured']) ? $data['is_featured'] : false;
        $data['type'] = $data['type'] ?? 'simple';
        $data = $this->handleTranslations($data);

        $product = DB::transaction(function () use ($data) {
            $product = $this->productRepo->create($data);
            $this->syncCategories($product, $data);
            $this->syncAttributes($product, $data);
            $this->uploadImages($product, $data);
            $this->syncRelations($product, $data);

            // Only sync variants for variable products
            if ($data['type'] === 'variable') {
                $this->syncProductVariants($product, $data);
            } else {
                // For simple products, ensure no variants exist
                $product->variants()->delete();
                // Sync branch stocks for simple products
                $this->syncBranchProductStocks($product, $data);
            }

            return $product;
        });

        return $product;
    }

    public function update(Product $product, array $data)
    {
        $data['is_new'] = isset($data['is_new']) ? $data['is_new'] : false;
        $data['is_active'] = isset($data['is_active']) ? $data['is_active'] : false;
        $data['is_featured'] = isset($data['is_featured']) ? $data['is_featured'] : false;
        $data['type'] = $data['type'] ?? $product->type ?? 'simple';

        $data = $this->handleTranslations($data);

        return DB::transaction(function () use ($data, $product) {
            // Store original type before update to check if we're changing from variable to simple
            $originalType = $product->type ?? 'simple';
            $isChangingToSimple = ($originalType === 'variable' && $data['type'] === 'simple');

            // If changing from variable to simple, clean up variant images before updating
            if ($isChangingToSimple) {
                // Load variants with images before deletion
                $product->load('variants.images');

                // Delete variant images from storage and database
                foreach ($product->variants as $variant) {
                    foreach ($variant->images as $image) {
                        if (Storage::disk('public')->exists($image->path)) {
                            Storage::disk('public')->delete($image->path);
                        }
                        $image->delete();
                    }
                }

                // Delete all variants (variant values will cascade delete due to foreign key)
                $product->variants()->delete();
            }

            $this->productRepo->update($product, $data);
            $this->syncCategories($product, $data);

            if (! empty($data['remove_images'])) {
                foreach ($data['remove_images'] as $imgId) {
                    $image = $product->images()->find($imgId);
                    if ($image) {
                        if (Storage::disk('public')->exists($image->path)) {
                            Storage::disk('public')->delete($image->path);
                        }
                        $image->delete();
                    }
                }
            }

            $this->syncAttributes($product, $data);
            $this->uploadImages($product, $data);
            $this->syncRelations($product, $data);

            // Only sync variants for variable products
            if ($data['type'] === 'variable') {
                $this->syncProductVariants($product, $data);
            } elseif (! $isChangingToSimple) {
                // For simple products that weren't just converted, ensure no variants exist
                // (This handles the case where a simple product might have variants somehow)
                $product->variants()->delete();
                // Sync branch stocks for simple products
                $this->syncBranchProductStocks($product, $data);
            }
            // notify users how has booking list for this product
            // $bookings = BookingList::where('product_id', $product->id)
            //     ->where('notified', false)
            //     ->orderBy('created_at')
            //     ->get();
            $bookings = $this->bookingListRepo->findByProductId($product->id);
            $stock = $product->manager()->stock();
            foreach ($bookings as $booking) {

                if ($stock >= $booking->quantity) {

                    $stock -= $booking->quantity;

                    $booking->update(['notified' => true]);

                    Mail::to($booking->user->email)->send(new ProductAvailableMail($booking));
                } else {
                    break;
                }
            }

            return $product->fresh();
        });
    }

    public function delete(Product $product)
    {
        foreach ($product->images as $img) {
            if (Storage::exists($img->path)) {
                Storage::delete($img->path);
            }
        }

        return $this->productRepo->delete($product);
    }

    protected function handleTranslations(array $data): array
    {
        // Translations are handled automatically by Spatie\Translatable
        // This method ensures translatable fields are properly formatted as arrays
        $translatable = ['name', 'description', 'short_description', 'meta_title', 'meta_description', 'meta_keywords'];

        foreach ($translatable as $field) {
            if (isset($data[$field]) && ! is_array($data[$field])) {
                // If a translatable field is not an array, wrap it
                $data[$field] = ['en' => $data[$field]];
            }
        }

        return $data;
    }

    protected function syncCategories(Product $product, array $data)
    {
        if (! empty($data['categories'])) {
            $product->categories()->sync($data['categories']);
        } else {
            $product->categories()->sync([]);
        }
    }

    protected function syncAttributes(Product $product, array $data)
    {

        if (! empty($data['attributes'])) {
            $syncData = [];
            foreach ($data['attributes'] as $attr) {
                $syncData[$attr['attribute_id']] = [
                    'attribute_option_id' => $attr['attribute_option_id'] ?? null,
                    'value' => $attr['value'] ?? null,
                ];
            }
            $product->attributes()->sync($syncData);
        }
    }

    protected function uploadImages(Product $product, array $data)
    {
        if (! empty($data['images']) && is_array($data['images'])) {
            foreach ($data['images'] as $image) {
                // Check if it's an uploaded file instance
                if ($image instanceof \Illuminate\Http\UploadedFile && $image->isValid()) {
                    $path = $image->store('products', 'public');

                    // Save each image in ProductImage model
                    $product->images()->create([
                        'path' => $path,
                    ]);
                }
            }
        }
    }

    public function bulkAction(array $ids, string $action)
    {
        return $this->productRepo->bulkAction($ids, $action);
    }

    protected function syncRelations(Product $product, array $data)
    {
        // clear old
        $this->relationRepo->deleteByProductId($product->id);

        // insert new
        foreach (['related' => 'related_products', 'upsell' => 'upsell_products', 'cross_sell' => 'cross_sell_products'] as $type => $field) {
            if (! empty($data[$field])) {
                foreach ($data[$field] as $index => $relatedId) {
                    $this->relationRepo->create([
                        'product_id' => $product->id,
                        'related_product_id' => $relatedId,
                        'type' => $type,
                    ]);
                }
            }
        }
    }

    public function search(string $term)
    {
        $products = $this->productRepo->search($term);

        return $products->transform(function ($product) {
            $firstImage = $product->images->first();

            return [
                'id' => $product->id,
                'name' => $product->name,
                'sku' => $product->sku,
                'price' => '0.00',
                'image' => $firstImage ? asset('storage/'.$firstImage->path) : asset('images/placeholder.png'),
            ];
        });
    }

    public function searchApi(string $term)
    {
        return $this->productRepo->search($term, activeOnly: true);
    }

    public function import(array $data)
    {
        Excel::queueImport(new ProductsImport, $data['products']);
    }

    protected function syncProductVariants(Product $product, array $data)
    {
        if (! empty($data['product_variants'])) {
            // Get IDs of variants that should be kept
            $submittedVariantIds = [];
            foreach ($data['product_variants'] as $variantData) {
                if (isset($variantData['id'])) {
                    $submittedVariantIds[] = $variantData['id'];
                }
            }

            // Delete variants that are not in the submitted data
            $product->variants()->whereNotIn('id', $submittedVariantIds)->delete();

            // Create/update variants
            foreach ($data['product_variants'] as $variantData) {
                $variantDataArray = [
                    'name' => $variantData['name'],
                    'slug' => $variantData['slug'],
                    'sku' => $variantData['sku'],
                    'price' => $variantData['price'],
                    'stock' => $variantData['stock'] ?? 0,
                    'is_active' => isset($variantData['is_active']) && $variantData['is_active'],
                ];

                if (isset($variantData['id'])) {
                    // Update existing variant
                    $variant = $product->variants()->find($variantData['id']);
                    if ($variant) {
                        $variant->update($variantDataArray);

                        // Handle image removal
                        if (! empty($variantData['remove_images'])) {
                            foreach ($variantData['remove_images'] as $imageId) {
                                if ($imageId) {
                                    $image = $variant->images()->find($imageId);
                                    if ($image) {
                                        if (Storage::disk('public')->exists($image->path)) {
                                            Storage::disk('public')->delete($image->path);
                                        }
                                        $image->delete();
                                    }
                                }
                            }
                        }
                    }
                } else {
                    // Create new variant
                    $variant = $product->variants()->create($variantDataArray);
                }

                // Update variant values (only for new variants or if explicitly provided)
                if (! isset($variantData['id']) && ! empty($variantData['variant_values'])) {
                    // Delete old values
                    $variant->values()->delete();

                    // Create new values
                    foreach ($variantData['variant_values'] as $valueData) {
                        $variantOption = VariantOption::find($valueData['variant_option_id']);
                        if ($variantOption) {
                            $variant->values()->create([
                                'variant_option_id' => $valueData['variant_option_id'],
                                'value' => $variantOption->name,
                            ]);
                        }
                    }
                }

                // Upload new variant images
                if (! empty($variantData['images']) && is_array($variantData['images'])) {
                    foreach ($variantData['images'] as $image) {
                        // Check if it's an uploaded file instance
                        if ($image instanceof \Illuminate\Http\UploadedFile && $image->isValid()) {
                            $path = $image->store('products/variants', 'public');
                            $variant->images()->create([
                                'path' => $path,
                            ]);
                        }
                    }
                }

                // Sync branch variant stocks
                $this->syncBranchVariantStocks($variant, $variantData);
            }
        } else {
            // If no variants submitted, delete all existing variants
            $product->variants()->delete();
        }
    }

    protected function syncBranchVariantStocks($variant, array $variantData)
    {
        if (isset($variantData['branch_stocks']) && is_array($variantData['branch_stocks'])) {
            // Update/create stocks for branches provided in the data
            foreach ($variantData['branch_stocks'] as $branchId => $quantity) {
                $newQuantity = $quantity ?? 0;
                $existingStock = BranchVariantStock::where('product_variant_id', $variant->id)
                    ->where('branch_id', $branchId)
                    ->first();

                $quantityBefore = $existingStock ? $existingStock->quantity : 0;

                BranchVariantStock::updateOrCreate(
                    [
                        'product_variant_id' => $variant->id,
                        'branch_id' => $branchId,
                    ],
                    [
                        'quantity' => $newQuantity,
                    ]
                );

                // Log stock history if quantity changed
                if ($quantityBefore != $newQuantity) {
                    $this->logBranchStockHistory([
                        'branch_id' => $branchId,
                        'product_id' => $variant->product_id,
                        'product_variant_id' => $variant->id,
                        'type' => 'manual_update',
                        'quantity_before' => $quantityBefore,
                        'quantity_after' => $newQuantity,
                        'quantity_change' => $newQuantity - $quantityBefore,
                        'notes' => 'Manual stock update from dashboard',
                    ]);
                }
            }

            // Ensure all branches have an entry (create missing ones with 0)
            $branches = \App\Models\Branch::all();
            foreach ($branches as $branch) {
                if (! isset($variantData['branch_stocks'][$branch->id])) {
                    BranchVariantStock::firstOrCreate(
                        [
                            'product_variant_id' => $variant->id,
                            'branch_id' => $branch->id,
                        ],
                        [
                            'quantity' => 0,
                        ]
                    );
                }
            }
        } else {
            // If no branch_stocks provided, ensure all branches have default 0 stock
            $branches = \App\Models\Branch::all();
            foreach ($branches as $branch) {
                BranchVariantStock::firstOrCreate(
                    [
                        'product_variant_id' => $variant->id,
                        'branch_id' => $branch->id,
                    ],
                    [
                        'quantity' => 0,
                    ]
                );
            }
        }
    }

    protected function syncBranchProductStocks($product, array $data)
    {
        if (isset($data['branch_stocks']) && is_array($data['branch_stocks'])) {
            // Update/create stocks for branches provided in the data
            foreach ($data['branch_stocks'] as $branchId => $quantity) {
                $newQuantity = $quantity ?? 0;
                $existingStock = BranchProductStock::where('product_id', $product->id)
                    ->where('branch_id', $branchId)
                    ->first();

                $quantityBefore = $existingStock ? $existingStock->quantity : 0;

                BranchProductStock::updateOrCreate(
                    [
                        'product_id' => $product->id,
                        'branch_id' => $branchId,
                    ],
                    [
                        'quantity' => $newQuantity,
                    ]
                );

                // Log stock history if quantity changed
                if ($quantityBefore != $newQuantity) {
                    $this->logBranchStockHistory([
                        'branch_id' => $branchId,
                        'product_id' => $product->id,
                        'product_variant_id' => null,
                        'type' => 'manual_update',
                        'quantity_before' => $quantityBefore,
                        'quantity_after' => $newQuantity,
                        'quantity_change' => $newQuantity - $quantityBefore,
                        'notes' => 'Manual stock update from dashboard',
                    ]);
                }
            }

            // Ensure all branches have an entry (create missing ones with 0)
            $branches = \App\Models\Branch::all();
            foreach ($branches as $branch) {
                if (! isset($data['branch_stocks'][$branch->id])) {
                    BranchProductStock::firstOrCreate(
                        [
                            'product_id' => $product->id,
                            'branch_id' => $branch->id,
                        ],
                        [
                            'quantity' => 0,
                        ]
                    );
                }
            }
        } else {
            // If no branch_stocks provided, ensure all branches have default 0 stock
            $branches = \App\Models\Branch::all();
            foreach ($branches as $branch) {
                BranchProductStock::firstOrCreate(
                    [
                        'product_id' => $product->id,
                        'branch_id' => $branch->id,
                    ],
                    [
                        'quantity' => 0,
                    ]
                );
            }
        }
    }

    public function updateBranchStocks(Product $product, array $data)
    {
        return DB::transaction(function () use ($product, $data) {
            if ($product->type === 'variable' && isset($data['product_variants'])) {
                // Update branch stocks for variants (branch-scoped)
                $branchId = Auth::user()->branch_id;
                foreach ($data['product_variants'] as $variantData) {
                    if (isset($variantData['id']) && isset($variantData['branch_stocks'][$branchId])) {
                        $variant = $product->variants()->find($variantData['id']);
                        if ($variant) {
                            $before = optional($variant->branchVariantStocks->where('branch_id', $branchId)->first())->quantity ?? 0;
                            $after = (int) ($variantData['branch_stocks'][$branchId] ?? $before);
                            $change = $after - $before;

                            $this->syncBranchVariantStocks($variant, $variantData);
                        }
                    }
                }
            } elseif ($product->type === 'simple' && isset($data['branch_stocks'])) {
                // Update branch stocks for simple product (branch-scoped)
                $branchId = Auth::user()->branch_id;
                $before = optional($product->branchProductStocks->where('branch_id', $branchId)->first())->quantity ?? 0;
                $after = (int) ($data['branch_stocks'][$branchId] ?? $before);
                $change = $after - $before;

                $this->syncBranchProductStocks($product, $data);
            }
            return $product->fresh();
        });
    }

    public function logBranchStockHistory(array $data)
    {
        BranchStockHistory::create([
            'branch_id' => $data['branch_id'],
            'product_id' => $data['product_id'] ?? null,
            'product_variant_id' => $data['product_variant_id'] ?? null,
            'order_id' => $data['order_id'] ?? null,
            'user_id' => $data['user_id'] ?? Auth::id(),
            'type' => $data['type'] ?? 'manual_update',
            'quantity_change' => $data['quantity_change'],
            'quantity_before' => $data['quantity_before'],
            'quantity_after' => $data['quantity_after'],
            'notes' => $data['notes'] ?? null,
        ]);
    }

    public function logOrderStockDecrease($branchId, $productId, $variantId, $orderId, $quantity, $quantityBefore, $quantityAfter)
    {
        $this->logBranchStockHistory([
            'branch_id' => $branchId,
            'product_id' => $productId,
            'product_variant_id' => $variantId,
            'order_id' => $orderId,
            'type' => 'order_decrease',
            'quantity_before' => $quantityBefore,
            'quantity_after' => $quantityAfter,
            'quantity_change' => -$quantity, // Negative for decrease
            'notes' => 'Stock decreased due to order',
        ]);
    }
}

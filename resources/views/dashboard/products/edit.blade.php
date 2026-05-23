@extends('dashboard.layouts.app')

@section('content')
    <div class="container-fluid py-4">
        <div class="card">
            <div class="card-header">
                <h6>Edit Product</h6>
            </div>
            <div class="card-body">
                <form id="productWizardForm" action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div id="productWizard">
                        <ul class="nav">
                            <li class="btn mx-1 nav-item"><a class="nav-link" href="#step-1">Basic Info</a></li>
                            <li class="btn mx-1 nav-item"><a class="nav-link" href="#step-2">Pricing & Stock</a></li>
                            <li class="btn mx-1 nav-item"><a class="nav-link" href="#step-3">Images</a></li>
                            <li class="btn mx-1 nav-item"><a class="nav-link" href="#step-4">Category</a></li>
                            <li class="btn mx-1 nav-item"><a class="nav-link" href="#step-5">Attributes</a></li>
                            <li class="btn mx-1 nav-item"><a class="nav-link" href="#step-6">Related Products</a></li>
                            <li class="btn mx-1 nav-item"><a class="nav-link" href="#step-7">SEO</a></li>
                        </ul>

                        <div class="tab-content">
                            <!-- Step 1: Basic Info -->
                            <div id="step-1" class="tab-pane" role="tabpanel">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label>Product Type <span class="text-danger">*</span></label>
                                        <select name="type" id="product_type" class="form-control" required>
                                            <option value="simple" {{ ($product->type ?? 'simple') == 'simple' ? 'selected' : '' }}>Simple</option>
                                            <option value="variable" {{ ($product->type ?? 'simple') == 'variable' ? 'selected' : '' }}>Variable</option>
                                        </select>
                                        <small class="text-muted">Simple: Single product with fixed price and stock. Variable: Product with multiple variants.</small>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label>SKU <span class="text-danger">*</span></label>
                                        <input type="text" name="sku" class="form-control"
                                            value="{{ $product->sku }}" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label>Name (EN) <span class="text-danger">*</span></label>
                                        <input type="text" name="name[en]" class="form-control"
                                            value="{{ $product->getTranslation('name', 'en') }}" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label>Name (AR) <span class="text-danger">*</span></label>
                                        <input type="text" name="name[ar]" class="form-control"
                                            value="{{ $product->getTranslation('name', 'ar') }}" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label>Slug <span class="text-danger">*</span></label>
                                        <input type="text" name="slug" class="form-control"
                                            value="{{ $product->slug }}" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label>Unit</label>
                                        <select name="unit_id" class="form-control">
                                            <option value="">-- Select Unit --</option>
                                            @if(isset($units) && $units->count() > 0)
                                                @foreach($units as $unit)
                                                    <option value="{{ $unit->id }}" {{ $product->unit_id == $unit->id ? 'selected' : '' }}>
                                                        {{ $unit->getTranslation('name', 'en') }}
                                                        ({{ is_array($unit->code) ? ($unit->code['en'] ?? '') : $unit->code }})
                                                    </option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label>Short Description (EN)</label>
                                    <textarea name="short_description[en]" class="form-control">{{ $product->getTranslation('short_description', 'en') }}</textarea>
                                </div>
                                <div class="mb-3">
                                    <label>Short Description (AR)</label>
                                    <textarea name="short_description[ar]" class="form-control">{{ $product->getTranslation('short_description', 'ar') }}</textarea>
                                </div>
                                <div class="mb-3">
                                    <label>Description (EN)</label>
                                    <textarea name="description[en]" class="form-control">{{ $product->getTranslation('description', 'en') }}</textarea>
                                </div>
                                <div class="mb-3">
                                    <label>Description (AR)</label>
                                    <textarea name="description[ar]" class="form-control">{{ $product->getTranslation('description', 'ar') }}</textarea>
                                </div>
                            </div>

                            <!-- Step 2: Pricing & Stock -->
                            <div id="step-2" class="tab-pane" role="tabpanel">
                                <!-- Simple Product Fields -->
                                <div id="simple-product-fields" style="{{ ($product->type ?? 'simple') == 'simple' ? '' : 'display: none;' }}">
                                    <h6>Pricing & Stock</h6>
                                    <p class="text-muted mb-4">Set the price and stock for this simple product</p>

                                    <div class="card mb-4">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-6 mb-3">
                                                    <label>Price <span class="text-danger">*</span></label>
                                                    <input type="number" name="price" id="product_price" class="form-control" step="0.01" min="0" placeholder="0.00" value="{{ $product->price ?? 0 }}" required>
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label>Stock</label>
                                                    <input type="number" name="stock" id="product_stock" class="form-control" min="0" placeholder="0" value="{{ $product->stock ?? 0 }}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Branch Stock Section (Only for Simple Products) -->
                                    <div class="card mb-4" id="product-branch-stocks-section" style="{{ ($product->type ?? 'simple') == 'variable' ? 'display: none;' : '' }}">
                                        <div class="card-header">
                                            <h6 class="mb-0">Branch Stock</h6>
                                        </div>
                                        <div class="card-body">
                                            <p class="text-muted mb-3">Set stock quantity for each branch (default: 0)</p>
                                            <div class="row">
                                                @php
                                                    $branches = isset($branches) ? $branches : \App\Models\Branch::all();
                                                @endphp
                                                @foreach ($branches as $branch)
                                                    @php
                                                        $branchStock = $product->branchProductStocks->where('branch_id', $branch->id)->first();
                                                        $stockValue = $branchStock ? $branchStock->quantity : 0;
                                                    @endphp
                                                    <div class="col-md-6 mb-3">
                                                        <label>{{ $branch->getTranslation('name', 'en') }}</label>
                                                        <input type="number"
                                                               name="branch_stocks[{{ $branch->id }}]"
                                                               class="form-control"
                                                               min="0"
                                                               placeholder="0"
                                                               value="{{ $stockValue }}">
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Store branches data for JavaScript -->
                                    <script>
                                        window.branchesData = @json($branches->map(function($branch) {
                                            return [
                                                'id' => $branch->id,
                                                'name' => $branch->getTranslation('name', 'en')
                                            ];
                                        }));
                                    </script>
                                </div>

                                <!-- Variable Product Fields -->
                                <div id="variable-product-fields" style="{{ ($product->type ?? 'simple') == 'variable' ? '' : 'display: none;' }}">
                                    <h6>Variants & Pricing</h6>
                                    <p class="text-muted mb-4">Select variant options and create product variants with pricing</p>

                                    <!-- Variant Options Selection -->
                                    @if(isset($variants) && is_object($variants) && $variants->count() > 0)
                                        <div class="card mb-4">
                                            <div class="card-header">
                                                <h6 class="mb-0">Select Variant Options</h6>
                                            </div>
                                            <div class="card-body">
                                                <div class="row">
                                                    @foreach ($variants as $variant)
                                                        <div class="col-md-6 mb-3">
                                                            <label class="form-label">
                                                                {{ $variant->getTranslation('name', 'en') }}
                                                                @if($variant->is_required)
                                                                    <span class="text-danger">*</span>
                                                                @endif
                                                            </label>
                                                            <div class="border rounded p-3" style="max-height: 200px; overflow-y: auto;">
                                                                @php
                                                                    $variantOptions = $variant->options;
                                                                    // Get selected option IDs for existing variants
                                                                    $selectedOptionIds = [];
                                                                    if ($product->variants) {
                                                                        foreach ($product->variants as $productVariant) {
                                                                            foreach ($productVariant->values as $value) {
                                                                                if ($value->variant_option_id && $variant->options->contains('id', $value->variant_option_id)) {
                                                                                    $selectedOptionIds[] = $value->variant_option_id;
                                                                                }
                                                                            }
                                                                        }
                                                                    }
                                                                @endphp
                                                                @if($variantOptions->count() > 0)
                                                                    @foreach($variantOptions as $option)
                                                                        <div class="form-check">
                                                                            <input
                                                                                class="form-check-input variant-option-checkbox"
                                                                                type="checkbox"
                                                                                name="variant_options[{{ $variant->id }}][]"
                                                                                value="{{ $option->id }}"
                                                                                id="variant_option_{{ $option->id }}"
                                                                                data-variant-id="{{ $variant->id }}"
                                                                                data-variant-name="{{ $variant->getTranslation('name', 'en') }}"
                                                                                data-option-name="{{ $option->getTranslation('name', 'en') }}"
                                                                                data-option-code="{{ $option->code }}"
                                                                                {{ in_array($option->id, $selectedOptionIds) ? 'checked' : '' }}>
                                                                            <label class="form-check-label" for="variant_option_{{ $option->id }}">
                                                                                {{ $option->getTranslation('name', 'en') }}
                                                                                <small class="text-muted">({{ $option->code }})</small>
                                                                            </label>
                                                                        </div>
                                                                    @endforeach
                                                                @else
                                                                    <p class="text-muted mb-0">No options available for this variant.</p>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Product Variants Table -->
                                        <div class="card mb-4">
                                            <div class="card-header d-flex justify-content-between align-items-center">
                                                <h6 class="mb-0">Product Variants</h6>
                                                <button type="button" class="btn btn-sm btn-primary" id="generate-variants-btn">
                                                    <i class="fa fa-magic"></i> Generate Combinations
                                                </button>
                                            </div>
                                            <div class="card-body">
                                                <div class="alert alert-info">
                                                    <i class="fa fa-info-circle"></i> Select variant options above, then click "Generate Combinations" to create product variants. Or edit existing variants below.
                                                </div>
                                                <div id="product-variants-container">
                                                    <table class="table table-bordered" id="product-variants-table" style="{{ $product->variants && $product->variants->count() > 0 ? '' : 'display: none;' }}">
                                                        <thead>
                                                            <tr>
                                                                <th>Variant Name</th>
                                                                <th>SKU</th>
                                                                <th>Price</th>
                                                                <th>Branch Stock</th>
                                                                <th>Images</th>
                                                                <th>Active</th>
                                                                <th>Actions</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody id="product-variants-tbody">
                                                            @foreach($product->variants as $index => $productVariant)
                                                                @php
                                                                    $variantValues = $productVariant->values->pluck('variant_option_id')->toArray();
                                                                    $variantName = $productVariant->getTranslation('name', 'en');
                                                                @endphp
                                                                <tr data-variant-index="{{ $index }}" data-variant-id="{{ $productVariant->id }}">
                                                                    <td>
                                                                        <input type="hidden" name="product_variants[{{ $index }}][id]" value="{{ $productVariant->id }}">
                                                                        <input type="text" name="product_variants[{{ $index }}][name][en]"
                                                                            class="form-control" value="{{ $variantName }}" required>
                                                                        <input type="text" name="product_variants[{{ $index }}][name][ar]"
                                                                            class="form-control mt-1" value="{{ $productVariant->getTranslation('name', 'ar') }}" placeholder="Arabic name" required>
                                                                        <input type="hidden" name="product_variants[{{ $index }}][slug]" value="{{ $productVariant->slug }}">
                                                                        @foreach($variantValues as $idx => $optId)
                                                                            <input type="hidden" name="product_variants[{{ $index }}][variant_values][{{ $idx }}][variant_option_id]" value="{{ $optId }}">
                                                                        @endforeach
                                                                    </td>
                                                                    <td>
                                                                        <input type="text" name="product_variants[{{ $index }}][sku]"
                                                                            class="form-control" value="{{ $productVariant->sku }}" required>
                                                                    </td>
                                                                    <td>
                                                                        <input type="number" name="product_variants[{{ $index }}][price]"
                                                                            class="form-control" step="0.01" min="0" value="{{ $productVariant->price }}" required>
                                                                    </td>
                                                                    <td>
                                                                        <div class="variant-branch-stocks-{{ $index }}" style="max-width: 200px; max-height: 150px; overflow-y: auto;">
                                                                            @php
                                                                                $branches = isset($branches) ? $branches : \App\Models\Branch::all();
                                                                                $productVariant->load('branchVariantStocks');
                                                                            @endphp
                                                                            @foreach($branches as $branch)
                                                                                @php
                                                                                    $variantBranchStock = $productVariant->branchVariantStocks->where('branch_id', $branch->id)->first();
                                                                                    $stockValue = $variantBranchStock ? $variantBranchStock->quantity : 0;
                                                                                @endphp
                                                                                <div class="mb-1">
                                                                                    <label class="text-xs" style="font-size: 0.7rem;">{{ $branch->getTranslation('name', 'en') }}</label>
                                                                                    <input type="number"
                                                                                           name="product_variants[{{ $index }}][branch_stocks][{{ $branch->id }}]"
                                                                                           class="form-control form-control-sm"
                                                                                           min="0"
                                                                                           placeholder="0"
                                                                                           value="{{ $stockValue }}"
                                                                                           style="font-size: 0.75rem;">
                                                                                </div>
                                                                            @endforeach
                                                                        </div>
                                                                    </td>
                                                                    <td>
                                                                        <div class="variant-images-container-{{ $index }}" style="max-width: 200px;">
                                                                            <div class="existing-variant-images mb-2">
                                                                                @foreach($productVariant->images as $img)
                                                                                    <div class="position-relative d-inline-block m-1" style="width: 60px; height: 60px;">
                                                                                        <img src="{{ asset('storage/' . $img->path) }}" class="rounded border" style="width: 100%; height: 100%; object-fit: cover;">
                                                                                        <button type="button" class="btn btn-sm btn-danger position-absolute top-0 end-0 remove-existing-variant-image"
                                                                                            data-image-id="{{ $img->id }}" style="width: 18px; height: 18px; padding: 0; font-size: 10px;">×</button>
                                                                                        <input type="hidden" name="product_variants[{{ $index }}][remove_images][]" value="" id="remove-variant-image-{{ $img->id }}">
                                                                                    </div>
                                                                                @endforeach
                                                                            </div>
                                                                            <input type="file"
                                                                                name="product_variants[{{ $index }}][images][]"
                                                                                class="form-control form-control-sm variant-image-input"
                                                                                accept="image/*"
                                                                                multiple
                                                                                data-variant-index="{{ $index }}"
                                                                                style="font-size: 0.75rem;">
                                                                            <div class="variant-images-preview-{{ $index }} d-flex flex-wrap gap-1 mt-2"></div>
                                                                        </div>
                                                                    </td>
                                                                    <td>
                                                                        <div class="form-check">
                                                                            <input type="checkbox" name="product_variants[{{ $index }}][is_active]"
                                                                                class="form-check-input" value="1" {{ $productVariant->is_active ? 'checked' : '' }}>
                                                                        </div>
                                                                    </td>
                                                                    <td>
                                                                        <button type="button" class="btn btn-sm btn-danger remove-variant-row"
                                                                            data-index="{{ $index }}">
                                                                            <i class="fa fa-trash"></i>
                                                                        </button>
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <div class="alert alert-warning">
                                            No variants available. Please create variants first.
                                        </div>
                                    @endif
                                </div>

                                <hr class="my-4">

                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label>Discount</label>
                                        <input type="number" name="discount" class="form-control"
                                            value="{{ $product->discount }}" step="0.01" min="0">
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label>Discount Type</label>
                                        <select name="discount_type" class="form-control">
                                            <option value="percentage" {{ ($product->discount_type ?? 'percentage') == 'percentage' ? 'selected' : '' }}>Percentage (%)</option>
                                            <option value="fixed" {{ ($product->discount_type ?? 'percentage') == 'fixed' ? 'selected' : '' }}>Fixed Amount</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label>Max Order Quantity</label>
                                        <input type="number" name="max_order_quantity" class="form-control"
                                            value="{{ $product->max_order_quantity ?? 1 }}" min="1">
                                    </div>
                                    <div class="col-md-12 mb-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="manage_stock"
                                                id="manage_stock" value="1"
                                                {{ $product->manage_stock ? 'checked' : '' }}>
                                            <label class="form-check-label" for="manage_stock">Manage Stock</label>
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <div class="form-check mt-4">
                                            <input type="checkbox" class="form-check-input" name="is_active"
                                                value="1" {{ $product->is_active ? 'checked' : '' }}>
                                            <label for="is_active" class="form-check-label">Active</label>
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <div class="form-check mt-4">
                                            <input type="checkbox" class="form-check-input" name="is_featured"
                                                value="1" {{ $product->is_featured ? 'checked' : '' }}>
                                            <label for="is_featured" class="form-check-label">Featured</label>
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <div class="form-check mt-4">
                                            <input type="checkbox" class="form-check-input" name="is_new"
                                                value="1" {{ $product->is_new ? 'checked' : '' }}>
                                            <label for="is_new" class="form-check-label">New</label>
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <div class="form-check mt-4">
                                            <input type="checkbox" class="form-check-input" name="is_bookable"
                                                value="1" id="is_bookable" {{ $product->is_bookable ? 'checked' : '' }}>
                                            <label for="is_bookable" class="form-check-label">Bookable</label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Step 3: Images -->
                            <div id="step-3" class="tab-pane" role="tabpanel">
                                <div class="mb-3">
                                    <label>Current Images</label>
                                    <div class="d-flex flex-wrap gap-2" id="imagesPreview">
                                        @foreach ($product->images as $image)
                                            <div class="position-relative m-2" style="width:180px; height:180px;">
                                                <img src="{{ asset('storage/' . $image->path) }}"
                                                    style="width:100%; height:100%; object-fit:cover;" class="rounded">

                                                <button type="button" class="btn btn-danger btn-sm remove-old-image"
                                                    data-id="{{ $image->id }}"
                                                    style="position:absolute; top:5px; right:5px;">x</button>


                                                <input type="hidden" name="remove_images[]" value=""
                                                    id="remove-image-{{ $image->id }}">
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label>Add New Images</label>
                                    <input type="file" name="images[]" id="imageInput" class="form-control" multiple>
                                </div>
                            </div>

                            <!-- Step 4: Category -->
                            <div id="step-4" class="tab-pane" role="tabpanel">
                                <label class="form-label">Categories (Multiple Selection Allowed)</label>
                                <div class="border rounded p-3" style="max-height: 300px; overflow-y: auto;">
                                    @php
                                        $selectedCategories = $product->categories->pluck('id')->toArray();
                                        $renderCheckboxes = function (
                                            $categories,
                                            $prefix = '',
                                            $selectedIds = [],
                                        ) use (&$renderCheckboxes) {
                                            foreach ($categories as $cat) {
                                                $isChecked = in_array($cat['id'], $selectedIds);
                                                echo '<div class="form-check">';
                                                echo '<input class="form-check-input" type="checkbox" name="categories[]" value="' .
                                                    $cat['id'] .
                                                    '" ' .
                                                    ($isChecked ? 'checked' : '') .
                                                    ' id="category_' .
                                                    $cat['id'] .
                                                    '">';
                                                echo '<label class="form-check-label" for="category_' .
                                                    $cat['id'] .
                                                    '">' .
                                                    $prefix .
                                                    ($cat['name']['en'] ?? 'No Name') .
                                                    '</label>';
                                                echo '</div>';
                                                if (!empty($cat['children'])) {
                                                    echo '<div class="ms-4">';
                                                    $renderCheckboxes($cat['children'], $prefix . '— ', $selectedIds);
                                                    echo '</div>';
                                                }
                                            }
                                        };
                                    @endphp
                                    {!! $renderCheckboxes($categories, '', $selectedCategories) !!}
                                </div>
                            </div>

                            <!-- Step 5: Attributes -->
                            <div id="step-5" class="tab-pane" role="tabpanel">
                                <h6>Select Attributes</h6>
                                <div class="row">
                                    @foreach ($attributes as $attribute)
                                        @php
                                            $pivot = $product->attributes->find($attribute->id)?->pivot;
                                        @endphp
                                        <div class="col-md-4 mb-3">
                                            <label>{{ $attribute->getTranslation('name', 'en') }}</label>
                                            @if ($attribute->options->count() > 0)
                                                <input type="hidden"
                                                    name="attributes[{{ $loop->index }}][attribute_id]"
                                                    value="{{ $attribute->id }}">
                                                <select name="attributes[{{ $loop->index }}][attribute_option_id]"
                                                    class="form-control">
                                                    <option value="">-- Select --</option>
                                                    @foreach ($attribute->options as $option)
                                                        <option value="{{ $option->id }}"
                                                            {{ $pivot && $pivot->attribute_option_id == $option->id ? 'selected' : '' }}>
                                                            {{ $option->value }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            @else
                                                <input type="hidden"
                                                    name="attributes[{{ $loop->index }}][attribute_id]"
                                                    value="{{ $attribute->id }}">
                                                <input type="text" name="attributes[{{ $loop->index }}][value]"
                                                    class="form-control" value="{{ $pivot->value ?? '' }}">
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Step 6: Related Products -->
                            <div id="step-6" class="tab-pane" role="tabpanel">
                                <h6>Select Related Products</h6>
                                <div class="border rounded p-3">
                                    <button type="button" class="btn btn-outline-primary" data-bs-toggle="offcanvas"
                                        data-bs-target="#relatedProductsCanvas">
                                        Add Related Products
                                    </button>
                                    <div id="relatedProductsWrapper">
                                        <table class="table" id="finalSelectedRelatedProductsTable">
                                            <thead>
                                                <tr>
                                                    <th>Image</th>
                                                    <th>Name</th>
                                                    <th>SKU</th>
                                                    <th>Price</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($product->relatedProducts as $related)
                                                    <tr id="related-{{ $related->id }}">
                                                        <td><img src="{{ asset('storage/' . $related->relatedProduct->images()->first()->path) }}"
                                                                width="50"></td>
                                                        <td>{{ $related->relatedProduct->name }}</td>
                                                        <td>{{ $related->relatedProduct->sku }}</td>
                                                        <td>{{ $related->relatedProduct->price }}</td>
                                                        <td>
                                                            <input type="hidden" name="related_products[]"
                                                                value="{{ $related->relatedProduct->id }}">
                                                            <button type="button"
                                                                class="btn btn-sm btn-danger remove-related"
                                                                data-id="{{ $related->id }}">Remove</button>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>

                                </div>
                                <h6 class="mt-4">Select Cross Sell Products</h6>
                                <div class="border rounded p-3">
                                    <button type="button" class="btn btn-outline-primary" data-bs-toggle="offcanvas"
                                        data-bs-target="#crossSellProductsCanvas">
                                        Add Cross Sell Products
                                    </button>
                                    <div id="crossSellProductsWrapper">
                                        <table class="table" id="finalSelectedCrossSellProductsTable">
                                            <thead>
                                                <tr>
                                                    <th>Image</th>
                                                    <th>Name</th>
                                                    <th>SKU</th>
                                                    <th>Price</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($product->crossSellProducts as $cross)
                                                    <tr id="cross-{{ $cross->id }}">
                                                        <td><img src="{{ asset('storage/' . $cross->relatedProduct->images()->first()->path) }}"
                                                                width="50"></td>
                                                        <td>{{ $cross->relatedProduct->name }}</td>
                                                        <td>{{ $cross->relatedProduct->sku }}</td>
                                                        <td>{{ $cross->relatedProduct->price }}</td>
                                                        <td>
                                                            <input type="hidden" name="cross_sell_products[]"
                                                                value="{{ $cross->relatedProduct->id }}">
                                                            <button type="button"
                                                                class="btn btn-sm btn-danger remove-cross"
                                                                data-id="{{ $cross->id }}">Remove</button>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <!-- Step 7: SEO -->
                            <div id="step-7" class="tab-pane" role="tabpanel">
                                <h6>SEO Fields</h6>
                                <div class="mb-3">
                                    <label>Meta Title (EN)</label>
                                    <input type="text" name="meta_title[en]" class="form-control"
                                        value="{{ $product->getTranslation('meta_title', 'en') }}">
                                </div>
                                <div class="mb-3">
                                    <label>Meta Title (AR)</label>
                                    <input type="text" name="meta_title[ar]" class="form-control"
                                        value="{{ $product->getTranslation('meta_title', 'ar') }}">
                                </div>
                                <div class="mb-3">
                                    <label>Meta Description (EN)</label>
                                    <textarea name="meta_description[en]" class="form-control">{{ $product->getTranslation('meta_description', 'en') }}</textarea>
                                </div>
                                <div class="mb-3">
                                    <label>Meta Description (AR)</label>
                                    <textarea name="meta_description[ar]" class="form-control">{{ $product->getTranslation('meta_description', 'ar') }}</textarea>
                                </div>
                                <div class="mb-3">
                                    <label>Meta Keywords (EN)</label>
                                    <input type="text" name="meta_keywords[en]" class="form-control"
                                        value="{{ $product->getTranslation('meta_keywords', 'en') }}">
                                </div>
                                <div class="mb-3">
                                    <label>Meta Keywords (AR)</label>
                                    <input type="text" name="meta_keywords[ar]" class="form-control"
                                        value="{{ $product->getTranslation('meta_keywords', 'ar') }}">
                                </div>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary mt-3">Update Product</button>
                </form>
            </div>
        </div>
        {{-- related products offcanvas --}}
        <div class="offcanvas offcanvas-end" tabindex="-1" id="relatedProductsCanvas">
            <div class="offcanvas-header">
                <h5 class="offcanvas-title">Select Related Products</h5>
                <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas"></button>
            </div>
            <div class="offcanvas-body">

                <!-- Search -->
                <input type="text" id="relatedProductSearch" class="form-control mb-3"
                    placeholder="Search products...">

                <!-- Results -->
                <div id="relatedProductResults" style="max-height: 400px; overflow-y: auto;"></div>

                <!-- Selected -->
                <h6 class="mt-3">Selected Products:</h6>
                <div id="selectedRelatedProducts" class="d-flex flex-wrap gap-2"></div>
            </div>
        </div>
        {{-- cross sell products offcanvas --}}
        <div class="offcanvas offcanvas-end" tabindex="-1" id="crossSellProductsCanvas">
            <div class="offcanvas-header">
                <h5 class="offcanvas-title">Select Cross Sell Products</h5>
                <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas"></button>
            </div>
            <div class="offcanvas-body">

                <!-- Search -->
                <input type="text" id="crossSellProductSearch" class="form-control mb-3"
                    placeholder="Search products...">

                <!-- Results -->
                <div id="crossSellProductResults" style="max-height: 400px; overflow-y: auto;"></div>

                <!-- Selected -->
                <h6 class="mt-3">Selected Products:</h6>
                <div id="selectedCrossSellProducts" class="d-flex flex-wrap gap-2"></div>
            </div>
        </div>

    </div>
@endsection
@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/smartwizard@5/dist/js/jquery.smartWizard.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://cdn.jsdelivr.net/npm/smartwizard@5/dist/css/smart_wizard.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js" integrity="sha512-VEd+nq25CkR676O+pLBnDW09R7VQX9Mdiij052gVCp5yVH3jGtH70Ho/UUv4mJDsEdTvqRCFZg0NKGiojGnUCw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.css" integrity="sha512-3pIirOrwegjM6erE5gPSwkUzO+3cTjpnV9lexlNZqvupR64iZBnOOTiiLPb9M36zpMScbmUNIcHUqKD47M719g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script>
        $(document).ready(function() {

            // Initialize wizard
            $('#productWizard').smartWizard({
                theme: 'dots',
                autoAdjustHeight: true,
                backButtonSupport: true,
                transition: {
                    animation: 'fade'
                },
                toolbarSettings: {
                    showNextButton: true,
                    showPreviousButton: true
                },
                anchorSettings: {
                    anchorClickable: true,
                    enableAllAnchors: true,
                    markDoneStep: true,
                    markAllPreviousStepsAsDone: true,
                    removeDoneStepOnNavigateBack: true
                }
            });

            // Product Type Toggle
            function toggleProductTypeFields() {
                const productType = $('#product_type').val();

                if (productType === 'simple') {
                    $('#simple-product-fields').show();
                    $('#variable-product-fields').hide();
                    $('#product-branch-stocks-section').show(); // Show branch stocks for simple products

                    // Make simple product fields required
                    $('#product_price').prop('required', true);
                    $('#product_stock').prop('required', false);

                    // Make variant fields not required
                    $('.variant-option-checkbox').prop('required', false);
                } else {
                    $('#simple-product-fields').hide();
                    $('#variable-product-fields').show();
                    $('#product-branch-stocks-section').hide(); // Hide branch stocks for variable products

                    // Make simple product fields not required
                    $('#product_price').prop('required', false);
                    $('#product_stock').prop('required', false);
                }
            }

            // Initialize on page load
            toggleProductTypeFields();

            // Toggle on change
            $('#product_type').on('change', function() {
                const selectElement = $(this);
                // Warn if switching from variable to simple with existing variants
                if (selectElement.val() === 'simple' && $('#product-variants-tbody tr').length > 0) {
                    Swal.fire({
                        title: 'Are you sure?',
                        text: 'Switching to simple product will delete all existing variants. Are you sure?',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Yes, switch to simple',
                        cancelButtonText: 'Cancel',
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33'
                    }).then((result) => {
                        if (!result.isConfirmed) {
                            selectElement.val('variable');
                            return;
                        }
                        toggleProductTypeFields();
                    });
                    return;
                }
                toggleProductTypeFields();
            });

            // Image preview
            const imageInput = document.getElementById('imageInput');
            const imagesPreview = document.getElementById('imagesPreview');

            imageInput.addEventListener('change', function() {

                const files = Array.from(this.files);

                files.forEach(file => {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const div = document.createElement('div');
                        div.className =
                            'relative border border-gray-300 rounded overflow-hidden position-relative text-center align-content-center';
                        div.style.width = '180px';
                        div.style.height = '180px';
                        div.style.position = 'relative';
                        div.innerHTML = `
                            <img src="${e.target.result}" class="object-cover w-full h-full" style="width:100%;height:100%">
                                <button type="button" class="position-absolute right-0 text-white bg-danger btn  rounded-full p-0 text-sm remove-btn"
                                style="position: absolute;top:0;right: 0;width: 25px;height: 25px;border-radius: 50%;align-content: center;padding: 0;">x</button>
                            `;
                        imagesPreview.appendChild(div);

                        // Remove button
                        div.querySelector('.remove-btn').addEventListener('click', function() {
                            div.remove();
                        });
                    }
                    reader.readAsDataURL(file);
                });
            });

            // Generate slug
            const nameInput = document.querySelector('input[name="name[en]"]');
            const slugInput = document.querySelector('input[name="slug"]');
            nameInput.addEventListener('input', function() {
                let slug = this.value.toLowerCase().replace(/ /g, '-').replace(/[^\w-]+/g, '');
                slugInput.value = slug;
            });

            // Form submission validation
            $('#productWizardForm').on('submit', function(e) {
                const productType = $('#product_type').val();

                if (productType === 'simple') {
                    // Validate simple product fields
                    const price = $('#product_price').val();
                    if (!price || parseFloat(price) < 0) {
                        e.preventDefault();
                        Swal.fire({
                            icon: 'error',
                            title: 'Validation Error',
                            text: 'Please enter a valid price for the simple product.',
                            confirmButtonText: 'OK',
                            confirmButtonColor: '#3085d6'
                        }).then(() => {
                            $('#productWizard').smartWizard('goToStep', 1); // Go to step 2
                        });
                        return false;
                    }
                    // Clear variant fields requirement
                    $('#product-variants-table input').prop('required', false);
                } else {
                    // Validate variable product fields
                    const variantCount = $('#product-variants-tbody tr').length;
                    if (variantCount === 0) {
                        e.preventDefault();
                        Swal.fire({
                            icon: 'error',
                            title: 'Validation Error',
                            text: 'Please generate at least one variant for variable products.',
                            confirmButtonText: 'OK',
                            confirmButtonColor: '#3085d6'
                        }).then(() => {
                            $('#productWizard').smartWizard('goToStep', 1); // Go to step 2
                        });
                        return false;
                    }
                    // Clear simple product fields requirement
                    $('#product_price').prop('required', false);
                    $('#product_stock').prop('required', false);
                }
            });
        });
        $(document).ready(function() {
            // State objects
            let selectedRelatedProducts = {};
            let selectedCrossSellProducts = {};

            // === Generic Search Handler ===
            function handleSearch(inputSelector, resultsSelector, selectedProducts, checkboxClass, addBtnId) {
                $(inputSelector).on('input', function() {
                    let query = $(this).val();

                    if (query.length < 2) {
                        $(resultsSelector).html('<p class="text-muted">Type at least 2 chars...</p>');
                        return;
                    }

                    $.get("{{ route('admin.products.search') }}", {
                        q: query
                    }, function(res) {
                        let html = '';
                        res.forEach(product => {
                            let isChecked = selectedProducts[product.id] ? 'checked' : '';

                            html += `
                                <div class="d-flex align-items-center border-bottom py-2 gap-3">
                                    <input type="checkbox" class="form-check-input ${checkboxClass} border"
                                        data-id="${product.id}"
                                        data-name="${product.name}"
                                        data-sku="${product.sku}"
                                        data-price="${product.price}"
                                        data-image="${product.image}"
                                        ${isChecked}>

                                    <img src="${product.image}" alt="${product.name}" width="50" height="50" class="rounded">

                                    <div class="flex-grow-1">
                                        <strong>${product.name}</strong><br>
                                        <small>SKU: ${product.sku} | Price: ${product.price}</small>
                                    </div>
                                </div>
                            `;
                        });

                        html += `<div class="mt-3">
                                    <button type="button" class="btn btn-success btn-sm" id="${addBtnId}">Add Selected</button>
                                </div>`;

                        $(resultsSelector).html(html);
                    });
                });
            }

            // === Related Products ===
            handleSearch('#relatedProductSearch', '#relatedProductResults', selectedRelatedProducts,
                'product-checkbox-related', 'addSelectedRelatedProducts');

            $(document).on('click', '#addSelectedRelatedProducts', function() {
                $('.product-checkbox-related:checked').each(function() {
                    let id = $(this).data('id');
                    let name = $(this).data('name');
                    let sku = $(this).data('sku');
                    let price = $(this).data('price');
                    let image = $(this).data('image');

                    if (!selectedRelatedProducts[id]) {
                        selectedRelatedProducts[id] = {
                            name,
                            sku,
                            price,
                            image
                        };

                        $('#finalSelectedRelatedProductsTable tbody').append(`
                            <tr id="related-${id}">
                                <td><img src="${image}" alt="${name}" width="50" height="50" class="rounded"></td>
                                <td>${name}</td>
                                <td>${sku}</td>
                                <td>${price}</td>
                                <td>
                                    <input type="hidden" name="related_products[]" value="${id}">
                                    <button type="button" class="btn btn-sm btn-danger remove-related" data-id="${id}">Remove</button>
                                </td>
                            </tr>
                        `);
                        $('#relatedProductsWrapper').show();
                    }
                });

                $('#relatedProductSearch').val('');
                $('#relatedProductResults').html('');
            });

            $(document).on('click', '.remove-related', function() {
                let id = $(this).data('id');
                delete selectedRelatedProducts[id];
                $(`#related-${id}`).remove();
            });

            // === Cross Sell Products ===
            handleSearch('#crossSellProductSearch', '#crossSellProductResults', selectedCrossSellProducts,
                'product-checkbox-cross', 'addSelectedCrossSellProducts');

            $(document).on('click', '#addSelectedCrossSellProducts', function() {
                $('.product-checkbox-cross:checked').each(function() {
                    let id = $(this).data('id');
                    let name = $(this).data('name');
                    let sku = $(this).data('sku');
                    let price = $(this).data('price');
                    let image = $(this).data('image');

                    if (!selectedCrossSellProducts[id]) {
                        selectedCrossSellProducts[id] = {
                            name,
                            sku,
                            price,
                            image
                        };

                        $('#finalSelectedCrossSellProductsTable tbody').append(`
                            <tr id="cross-${id}">
                                <td><img src="${image}" alt="${name}" width="50" height="50" class="rounded"></td>
                                <td>${name}</td>
                                <td>${sku}</td>
                                <td>${price}</td>
                                <td>
                                    <input type="hidden" name="cross_sell_products[]" value="${id}">
                                    <button type="button" class="btn btn-sm btn-danger remove-cross" data-id="${id}">Remove</button>
                                </td>
                            </tr>
                        `);
                        $('#crossSellProductsWrapper').show();

                    }
                });

                $('#crossSellProductSearch').val('');
                $('#crossSellProductResults').html('');
            });

            $(document).on('click', '.remove-cross', function() {
                let id = $(this).data('id');
                delete selectedCrossSellProducts[id];
                $(`#cross-${id}`).remove();
            });
        });

        document.getElementById('manage_stock').addEventListener('change', function() {
            // Stock management is now handled at product level
        });

        // === Variant Combinations Generation ===
        let variantCombinations = [];
        let variantIndex = {{ $product->variants ? $product->variants->count() : 0 }};

        // Function to generate cartesian product of arrays
        function cartesianProduct(arrays) {
            return arrays.reduce((acc, arr) => {
                return acc.flatMap(x => arr.map(y => [...x, y]));
            }, [[]]);
        }

        // Generate variant combinations
        $('#generate-variants-btn').on('click', function() {
            // Get selected options grouped by variant
            let selectedOptionsByVariant = {};
            $('.variant-option-checkbox:checked').each(function() {
                let variantId = $(this).data('variant-id');
                if (!selectedOptionsByVariant[variantId]) {
                    selectedOptionsByVariant[variantId] = [];
                }
                selectedOptionsByVariant[variantId].push({
                    id: $(this).val(),
                    variantId: variantId,
                    variantName: $(this).data('variant-name'),
                    optionName: $(this).data('option-name'),
                    optionCode: $(this).data('option-code')
            });
        });

            // Check if any options are selected
            if (Object.keys(selectedOptionsByVariant).length === 0) {
                Swal.fire({
                    icon: 'warning',
                    title: 'No Options Selected',
                    text: 'Please select at least one variant option.',
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#3085d6'
                });
                return;
            }

            // Generate all combinations
            let optionArrays = Object.values(selectedOptionsByVariant);
            let combinations = cartesianProduct(optionArrays);

            if (combinations.length === 0) {
                Swal.fire({
                    icon: 'info',
                    title: 'No Combinations',
                    text: 'No combinations to generate.',
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#3085d6'
                });
                return;
            }

            // Don't clear existing rows, just append new ones (skip duplicates)
            variantCombinations = [];
            const existingKeys = (function() {
                const keys = new Set();
                $('#product-variants-tbody tr').each(function() {
                    const ids = [];
                    $(this).find('input[name*="[variant_values]"][name$="[variant_option_id]"]').each(function() {
                        ids.push($(this).val());
                    });
                    if (ids.length) {
                        keys.add(ids.map(String).sort().join('-'));
                    }
                });
                return keys;
            })();
            let createdCount = 0;

            // Create rows for each combination
            combinations.forEach((combination) => {
                let variantName = combination.map(c => c.optionName).join(' / ');
                let variantCodes = combination.map(c => c.optionCode).join('-');
                let optionIds = combination.map(c => c.id);

                // Skip if this combination already exists
                const comboKey = optionIds.map(String).sort().join('-');
                if (existingKeys.has(comboKey)) {
                    return;
                }
                existingKeys.add(comboKey);

                // Generate unique slug by combining product base SKU with variant codes
                let baseSku = $('input[name="sku"]').val() || 'PROD';
                let uniqueSlug = baseSku.toLowerCase() + '-' + variantCodes.toLowerCase().replace(/[^a-z0-9-]/g, '-');

                variantCombinations.push({
                    index: variantIndex,
                    variantName: variantName,
                    optionIds: optionIds,
                    codes: variantCodes
                });

                let row = `
                    <tr data-variant-index="${variantIndex}">
                        <td>
                            <input type="text" name="product_variants[${variantIndex}][name][en]"
                                class="form-control" value="${variantName}" required>
                            <input type="text" name="product_variants[${variantIndex}][name][ar]"
                                class="form-control mt-1" placeholder="Arabic name" required>
                            <input type="hidden" name="product_variants[${variantIndex}][slug]"
                                value="${uniqueSlug}">
                            ${optionIds.map((optId, idx) => `
                                <input type="hidden" name="product_variants[${variantIndex}][variant_values][${idx}][variant_option_id]" value="${optId}">
                            `).join('')}
                        </td>
                        <td>
                            <input type="text" name="product_variants[${variantIndex}][sku]"
                                class="form-control" placeholder="SKU"
                                value="${baseSku}-${variantCodes}" required>
                        </td>
                        <td>
                            <input type="number" name="product_variants[${variantIndex}][price]"
                                class="form-control" step="0.01" min="0" placeholder="0.00" required>
                        </td>
                        <td>
                            <div class="variant-branch-stocks-${variantIndex}" style="max-width: 200px; max-height: 150px; overflow-y: auto;">
                                ${(typeof window.branchesData !== 'undefined' && window.branchesData.length > 0) ?
                                    window.branchesData.map(function(branch) {
                                        return '<div class="mb-1"><label class="text-xs" style="font-size: 0.7rem;">' + branch.name + '</label><input type="number" name="product_variants[' + variantIndex + '][branch_stocks][' + branch.id + ']" class="form-control form-control-sm" min="0" placeholder="0" value="0" style="font-size: 0.75rem;"></div>';
                                    }).join('') :
                                    '<p class="text-muted text-xs">No branches</p>'
                                }
                            </div>
                        </td>
                        <td>
                            <div class="variant-images-container-${variantIndex}" style="max-width: 200px;">
                                <input type="file"
                                    name="product_variants[${variantIndex}][images][]"
                                    class="form-control form-control-sm variant-image-input"
                                    accept="image/*"
                                    multiple
                                    data-variant-index="${variantIndex}"
                                    style="font-size: 0.75rem;">
                                <div class="variant-images-preview-${variantIndex} d-flex flex-wrap gap-1 mt-2"></div>
                            </div>
                        </td>
                        <td>
                            <div class="form-check">
                                <input type="checkbox" name="product_variants[${variantIndex}][is_active]"
                                    class="form-check-input" value="1" checked>
                            </div>
                        </td>
                        <td>
                            <button type="button" class="btn btn-sm btn-danger remove-variant-row"
                                data-index="${variantIndex}">
                                <i class="fa fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                `;
                $('#product-variants-tbody').append(row);
                variantIndex++;
                createdCount++;
            });

            // Show the table
            $('#product-variants-table').show();

            // Update alert
            if (createdCount === 0) {
                $('#product-variants-container .alert').html(
                    `<i class="fa fa-info-circle"></i> All selected combinations already exist.`
                ).removeClass('alert-success').addClass('alert-info');
            } else {
                $('#product-variants-container .alert').html(
                    `<i class="fa fa-check-circle"></i> Added ${createdCount} new variant combination(s). Fill in the details below.`
                ).removeClass('alert-info').addClass('alert-success');
            }
        });

        // Remove variant row
        $(document).on('click', '.remove-variant-row', function() {
            let index = $(this).data('index');
            $(this).closest('tr').remove();
            variantCombinations = variantCombinations.filter(c => c.index !== index);

            if ($('#product-variants-tbody tr').length === 0) {
                $('#product-variants-table').hide();
            }
        });

        // Handle variant image uploads and previews
        $(document).on('change', '.variant-image-input', function() {
            let variantIndex = $(this).data('variant-index');
            let previewContainer = $(`.variant-images-preview-${variantIndex}`);
            let files = this.files;

            // Don't clear existing previews, just add new ones
            Array.from(files).forEach((file, index) => {
                if (file.type.startsWith('image/')) {
                    let reader = new FileReader();
                    reader.onload = function(e) {
                        let imageDiv = $(`
                            <div class="position-relative" style="width: 60px; height: 60px; margin: 2px;">
                                <img src="${e.target.result}"
                                     class="rounded border"
                                     style="width: 100%; height: 100%; object-fit: cover;">
                                <button type="button"
                                        class="btn btn-sm btn-danger position-absolute top-0 end-0 remove-variant-image"
                                        data-variant-index="${variantIndex}"
                                        data-file-index="${index}"
                                        style="width: 18px; height: 18px; padding: 0; font-size: 10px; line-height: 1;">
                                    ×
                                </button>
                            </div>
                        `);
                        previewContainer.append(imageDiv);
                    };
                    reader.readAsDataURL(file);
                }
            });
        });

        // Remove variant image from preview
        $(document).on('click', '.remove-variant-image', function() {
            let variantIndex = $(this).data('variant-index');
            let fileIndex = $(this).data('file-index');
            let input = $(`.variant-image-input[data-variant-index="${variantIndex}"]`)[0];

            if (input && input.files) {
                // Create a new FileList without the removed file
                let dt = new DataTransfer();
                Array.from(input.files).forEach((file, index) => {
                    if (index !== fileIndex) {
                        dt.items.add(file);
                    }
                });
                input.files = dt.files;

                // Re-trigger change event to update preview
                $(input).trigger('change');
            }

            $(this).closest('div').remove();
        });

        // Remove existing variant image
        $(document).on('click', '.remove-existing-variant-image', function() {
            let imageId = $(this).data('image-id');
            $(`#remove-variant-image-${imageId}`).val(imageId);
            $(this).closest('div').hide();
        });
        $(document).ready(function() {
            $(document).on('click', '.remove-old-image', function() {
                let id = $(this).data('id');
                $(this).parent().remove();
                $('#remove-image-' + id).val(id);
                // send ajax request to remove image
                $.ajax({    url: '{{ route("admin.products.removeImage", ":id") }}'.replace(':id', id),
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                    },
                    success: function() {
                        toastr.success('Image removed');
                    },
                    error: function() {
                        toastr.error('Something went wrong');
                    }
                });
            });
        });
    </script>
@endpush

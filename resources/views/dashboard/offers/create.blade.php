@extends('dashboard.layouts.app')

@section('content')
    <div class="container-fluid py-4">
        <div class="card">
            <div class="card-header">
                <h6>{{ __('Add Offer') }}</h6>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.offers.store') }}" method="POST" id="offerForm" enctype="multipart/form-data">
                    @csrf

                    <!-- Basic Information -->
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label>{{ __('Title') }} <span class="text-danger">*</span></label>
                            <input type="text" name="title" class="form-control" value="{{ old('title') }}" required>
                            @error('title')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Image -->
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label>{{ __('Image') }}</label>
                            <input type="file" name="image" class="form-control" accept="image/*">
                            <small class="text-muted">{{ __('Recommended resolution: 560px X 609px') }}</small>
                            <img id="preview_image" src="" alt="Image Preview"
                            class="mt-2"style="max-height:100px; display:none;">
                            @error('image')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Offer Type -->
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>{{ __('Offer Type') }} <span class="text-danger">*</span></label>
                            <select name="type" id="offer_type" class="form-control" required>
                                <option value="">{{ __('-- Select Type --') }}</option>
                                <option value="product" {{ old('type') == 'product' ? 'selected' : '' }}>{{ __('Product') }}</option>
                                <option value="category" {{ old('type') == 'category' ? 'selected' : '' }}>{{ __('Category') }}</option>
                                <option value="cart" {{ old('type') == 'cart' ? 'selected' : '' }}>{{ __('Cart') }}</option>
                                <option value="shipping" {{ old('type') == 'shipping' ? 'selected' : '' }}>{{ __('Shipping') }}</option>
                            </select>
                            @error('type')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>{{ __('Discount Type') }} <span class="text-danger">*</span></label>
                            <select name="discount_type" id="discount_type" class="form-control" required>
                                <option value="">{{ __('-- Select Discount Type --') }}</option>
                                <option value="fixed" {{ old('discount_type') == 'fixed' ? 'selected' : '' }}>{{ __('Fixed Amount') }}</option>
                                <option value="percent" {{ old('discount_type') == 'percent' ? 'selected' : '' }}>{{ __('Percentage') }}</option>
                                <option value="free_shipping" {{ old('discount_type') == 'free_shipping' ? 'selected' : '' }}>{{ __('Free Shipping') }}</option>
                                <option value="bogo" {{ old('discount_type') == 'bogo' ? 'selected' : '' }}>{{ __('Buy One Get One') }}</option>
                            </select>
                            @error('discount_type')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Discount Value -->
                    <div class="row">
                        <div class="col-md-6 mb-3" id="discount_value_wrapper">
                            <label>{{ __('Discount Value') }} <span class="text-danger" id="discount_value_required" style="display: none;">*</span></label>
                            <input type="number" step="0.01" min="0" name="discount_value"
                                class="form-control" value="{{ old('discount_value', 0) }}"
                                id="discount_value_input">
                            @error('discount_value')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">{{ __('Leave 0 for free shipping or BOGO offers') }}</small>
                        </div>
                    </div>

                    <!-- Conditions based on offer type -->
                    <div id="conditions_section" style="display: none;">
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <h6 class="mb-3">{{ __('Conditions') }}</h6>

                                <!-- Product Condition -->
                                <div id="condition_product" style="display: none;">
                                    <label>{{ __('Select Product') }}</label>
                                    <select name="condition[product_id]" id="product_select" class="form-control">
                                        <option value="">{{ __('-- Select Product --') }}</option>
                                        @if(isset($products))
                                            @foreach($products as $product)
                                                <option value="{{ $product->id }}"
                                                    {{ old('condition.product_id') == $product->id ? 'selected' : '' }}>
                                                    {{ $product->getTranslation('name', app()->getLocale()) }}
                                                </option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>

                                <!-- Category Condition -->
                                <div id="condition_category" style="display: none;">
                                    <label>{{ __('Select Category') }}</label>
                                    <select name="condition[category_id]" id="category_select" class="form-control">
                                        <option value="">{{ __('-- Select Category --') }}</option>
                                        @if(isset($categories))
                                            @foreach($categories as $category)
                                                <option value="{{ $category->id }}"
                                                    {{ old('condition.category_id') == $category->id ? 'selected' : '' }}>
                                                    {{ $category->getTranslation('name', app()->getLocale()) }}
                                                </option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>

                                <!-- Cart Condition -->
                                <div id="condition_cart" style="display: none;">
                                    <label>{{ __('Minimum Cart Amount') }}</label>
                                    <input type="number" step="0.01" min="0" name="condition[min_cart_amount]"
                                        class="form-control" value="{{ old('condition.min_cart_amount', 0) }}"
                                        placeholder="{{ __('Enter minimum cart amount (0 for no minimum)') }}">
                                </div>

                                <!-- Shipping Condition (no specific condition needed) -->
                                <div id="condition_shipping" style="display: none;">
                                    <p class="text-muted">{{ __('No additional conditions required for shipping offers.') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Date Range -->
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>{{ __('Start Date') }}</label>
                            <input type="datetime-local" name="start_date" class="form-control"
                                value="{{ old('start_date') }}">
                            @error('start_date')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">{{ __('Leave empty for no start date limit') }}</small>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>{{ __('End Date') }}</label>
                            <input type="datetime-local" name="end_date" class="form-control"
                                value="{{ old('end_date') }}">
                            @error('end_date')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">{{ __('Leave empty for no expiration') }}</small>
                        </div>
                    </div>

                    <!-- Active Status -->
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="is_active" id="is_active"
                                    value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">
                                    {{ __('Active') }}
                                </label>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <button class="btn btn-primary mt-3" type="submit">{{ __('Save Offer') }}</button>
                    <a href="{{ route('admin.offers.index') }}" class="btn btn-secondary mt-3">{{ __('Cancel') }}</a>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            // Handle offer type change
            $('#offer_type').on('change', function() {
                const type = $(this).val();

                // Hide all condition sections
                $('#condition_product, #condition_category, #condition_cart, #condition_shipping').hide();
                $('#conditions_section').hide();

                // Show relevant condition section
                if (type) {
                    $('#conditions_section').show();
                    if (type === 'product') {
                        $('#condition_product').show();
                    } else if (type === 'category') {
                        $('#condition_category').show();
                    } else if (type === 'cart') {
                        $('#condition_cart').show();
                    } else if (type === 'shipping') {
                        $('#condition_shipping').show();
                    }
                }
            });

            // Handle discount type change
            $('#discount_type').on('change', function() {
                const discountType = $(this).val();

                if (discountType === 'free_shipping' || discountType === 'bogo') {
                    $('#discount_value_input').val(0).prop('disabled', true);
                    $('#discount_value_required').hide();
                } else {
                    $('#discount_value_input').prop('disabled', false);
                    $('#discount_value_required').show();
                }
            });

            // Trigger on page load if values exist
            if ($('#offer_type').val()) {
                $('#offer_type').trigger('change');
            }
            if ($('#discount_type').val()) {
                $('#discount_type').trigger('change');
            }

            // Initialize Select2 for product and category selects if available
            if (typeof $.fn.select2 !== 'undefined') {
                $('#product_select, #category_select').select2({
                    placeholder: "{{ __('-- Select --') }}",
                    allowClear: true,
                    width: '100%'
                });
            }
        });
        document.getElementById('image').addEventListener('change', function() {
            const [file] = this.files;
            if (file) {
                const preview = document.getElementById('preview_image');
                preview.src = URL.createObjectURL(file);
                preview.style.display = 'block';
            }
        });
    </script>
@endpush


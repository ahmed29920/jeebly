@php
    $oldProductVariants = array_values(old('product_variants', []));
    $oldVariantOptions = old('variant_options', []);
    $oldRelatedProductIds = old('related_products', []);
    $oldCrossSellProductIds = old('cross_sell_products', []);
    $oldRelatedProducts = $oldRelatedProductIds
        ? \App\Models\Product::with('images')->whereIn('id', $oldRelatedProductIds)->get()->keyBy('id')
        : collect();
    $oldCrossSellProducts = $oldCrossSellProductIds
        ? \App\Models\Product::with('images')->whereIn('id', $oldCrossSellProductIds)->get()->keyBy('id')
        : collect();
@endphp

<script>
    function appendVariantRow(variantData, index) {
        const optionIds = (variantData.variant_values || []).map(v => String(v.variant_option_id));
        const nameEn = (variantData.name && variantData.name.en) ? variantData.name.en : (variantData.name || '');
        const nameAr = (variantData.name && variantData.name.ar) ? variantData.name.ar : '';
        const slug = variantData.slug || '';
        const sku = variantData.sku || '';
        const price = variantData.price ?? '';
        const isActive = variantData.is_active === '1' || variantData.is_active === 1 || variantData.is_active === true;
        const branchStocks = variantData.branch_stocks || {};
        const variantId = variantData.id || '';

        let row = `
            <tr data-variant-index="${index}">
                <td>
                    ${variantId ? `<input type="hidden" name="product_variants[${index}][id]" value="${variantId}">` : ''}
                    <input type="text" name="product_variants[${index}][name][en]"
                        class="form-control" value="${String(nameEn).replace(/"/g, '&quot;')}" required>
                    <input type="text" name="product_variants[${index}][name][ar]"
                        class="form-control mt-1" placeholder="Arabic name" value="${String(nameAr).replace(/"/g, '&quot;')}" required>
                    <input type="hidden" name="product_variants[${index}][slug]" value="${String(slug).replace(/"/g, '&quot;')}">
                    ${optionIds.map((optId, idx) => `
                        <input type="hidden" name="product_variants[${index}][variant_values][${idx}][variant_option_id]" value="${optId}">
                    `).join('')}
                </td>
                <td>
                    <input type="text" name="product_variants[${index}][sku]"
                        class="form-control" placeholder="SKU"
                        value="${String(sku).replace(/"/g, '&quot;')}" required>
                </td>
                <td>
                    <input type="number" name="product_variants[${index}][price]"
                        class="form-control" step="0.01" min="0" placeholder="0.00"
                        value="${price}" required>
                </td>
                <td>
                    <div class="variant-branch-stocks-${index}" style="max-width: 200px; max-height: 150px; overflow-y: auto;">
                        ${(typeof window.branchesData !== 'undefined' && window.branchesData.length > 0) ?
                            window.branchesData.map(function(branch) {
                                const stockVal = branchStocks[branch.id] ?? branchStocks[String(branch.id)] ?? 0;
                                return '<div class="mb-1"><label class="text-xs" style="font-size: 0.7rem;">' + branch.name + '</label><input type="number" name="product_variants[' + index + '][branch_stocks][' + branch.id + ']" class="form-control form-control-sm" min="0" placeholder="0" value="' + stockVal + '" style="font-size: 0.75rem;"></div>';
                            }).join('') :
                            '<p class="text-muted text-xs">No branches</p>'
                        }
                    </div>
                </td>
                <td>
                    <div class="variant-images-container-${index}" style="max-width: 200px;">
                        <input type="file"
                            name="product_variants[${index}][images][]"
                            class="form-control form-control-sm variant-image-input"
                            accept="image/*"
                            multiple
                            data-variant-index="${index}"
                            style="font-size: 0.75rem;">
                        <div class="variant-images-preview-${index} d-flex flex-wrap gap-1 mt-2"></div>
                    </div>
                </td>
                <td>
                    <div class="form-check">
                        <input type="checkbox" name="product_variants[${index}][is_active]"
                            class="form-check-input" value="1" ${isActive ? 'checked' : ''}>
                    </div>
                </td>
                <td>
                    <button type="button" class="btn btn-sm btn-danger remove-variant-row"
                        data-index="${index}">
                        <i class="fa fa-trash"></i>
                    </button>
                </td>
            </tr>
        `;

        $('#product-variants-tbody').append(row);
    }

    function restoreProductFormState() {
        const oldVariants = @json($oldProductVariants);
        const oldRelatedProducts = @json($oldRelatedProducts->values());
        const oldCrossSellProducts = @json($oldCrossSellProducts->values());

        if ($('#product_type').val() === 'variable') {
            $('#simple-product-fields').hide();
            $('#variable-product-fields').show();
            $('#product-branch-stocks-section').hide();
        }

        if (oldVariants.length > 0) {
            $('#product-variants-tbody').empty();
            oldVariants.forEach((variantData, index) => {
                appendVariantRow(variantData, index);
            });
            $('#product-variants-table').show();
            $('#product-variants-container .alert').html(
                `<i class="fa fa-check-circle"></i> Restored ${oldVariants.length} variant(s) from your previous submission.`
            ).removeClass('alert-info').addClass('alert-success');
        }

        if (oldRelatedProducts.length > 0) {
            $('#finalSelectedRelatedProductsTable tbody').empty();
            if (!window.selectedRelatedProducts) {
                window.selectedRelatedProducts = {};
            } else {
                window.selectedRelatedProducts = {};
            }

            oldRelatedProducts.forEach(function(product) {
                const id = product.id;
                const name = (typeof product.name === 'object' ? product.name.en : product.name) || '';
                const sku = product.sku || '';
                const price = product.price || '';
                const image = (product.images && product.images[0]) ? `/storage/${product.images[0].path}` : '';

                window.selectedRelatedProducts[id] = { name, sku, price, image };

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
            });
            $('#relatedProductsWrapper').show();
        }

        if (oldCrossSellProducts.length > 0) {
            $('#finalSelectedCrossSellProductsTable tbody').empty();
            window.selectedCrossSellProducts = {};

            oldCrossSellProducts.forEach(function(product) {
                const id = product.id;
                const name = (typeof product.name === 'object' ? product.name.en : product.name) || '';
                const sku = product.sku || '';
                const price = product.price || '';
                const image = (product.images && product.images[0]) ? `/storage/${product.images[0].path}` : '';

                window.selectedCrossSellProducts[id] = { name, sku, price, image };

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
            });
            $('#crossSellProductsWrapper').show();
        }
    }
</script>

@extends('dashboard.layouts.app')

@section('content')
    <div class="container-fluid py-4">
        <div class="row mb-3">
            <div class="col-6">
                <button class="btn btn-outline-secondary" type="button" data-bs-toggle="offcanvas"
                    data-bs-target="#filterOffcanvas" aria-controls="filterOffcanvas">
                    <i class="fa fa-filter"></i> {{ __('Filter') }}
                </button>
                 <a class="btn btn-outline-secondary" href="{{ route('admin.products.export') }}">
                    <i class="fa-solid fa-file-export"></i> {{ __('Export') }}
                </a>
                <a class="btn btn-outline-secondary" href="{{ route('admin.products.import') }}">
                    <i class="fa-solid fa-file-import"></i> {{ __('Import') }}
                </a>
            </div>
            <div class="col-6 text-end">
                <a href="{{ route('admin.products.create') }}" class="btn btn-primary">
                    <i class="fa fa-plus"></i> {{ __('Add Product') }}
                </a>
            </div>
        </div>

        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6>Products</h6>
            </div>

            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div id="products-table_length"></div>

                    <form id="bulk-action-form" action="{{ route('admin.products.bulk') }}" method="POST" class="d-flex">
                        @csrf
                        <select name="action" id="bulk-action-select" class="form-control form-control-sm me-2" disabled
                            required>
                            <option value="">-- Bulk Action --</option>
                            <option value="delete">Delete</option>
                            <option value="activate">Set Active</option>
                            <option value="deactivate">Set Inactive</option>
                            <option value="featured">Set Featured</option>
                            <option value="not-featured">Set Not Featured</option>
                            <option value="new">Set New</option>
                            <option value="not-new">Set Not New</option>
                        </select>
                        <button type="submit" id="bulk-apply-btn"
                            class="btn btn-sm m-0 btn-secondary d-none">Apply</button>
                    </form>
                    <div id="products-table_filter"></div>
                </div>
                <table id="products-table" class="table">
                    <thead>
                        <tr>
                            <th class="text-center">
                                <input type="checkbox" id="select-all">
                            </th>
                            <th class="text-center">ID</th>
                            <th class="text-center">Name</th>
                            <th class="text-center">Category</th>
                            <th class="text-center">Image</th>
                            <th class="text-center">Price</th>
                            <th class="text-center">Stock</th>
                            <th class="text-center">Unit</th>
                            <th class="text-center">Active</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($products as $product)
                            <tr>
                                <td class="text-center">
                                    <input type="checkbox" name="ids[]" value="{{ $product->id }}"
                                        form="bulk-action-form">
                                </td>
                                <td class="align-content-center text-center">{{ $product->id }}</td>
                                <td class="align-content-center text-center">
                                    {{ $product->getTranslation('name', 'en') }} , <br>
                                    {{ $product->getTranslation('name', 'ar') }}
                                </td>
                                <td class="align-content-center text-center">
                                    {{ $product->categories->pluck('name')->implode(', ') }}
                                </td>
                                <td class="align-content-center text-center">
                                    <img src="{{ $product->images()->first()?->image_path }}"
                                        alt="{{ $product->getTranslation('name', 'en') }}" class="avatar avatar-xl">
                                </td>
                                <td class="text-center align-content-center">
                                    {{ format_currency($product->manager()->price()) }}
                                </td>
                                <td class="text-center align-content-center">
                                    {{ $product->manager()->stock() }}
                                </td>
                                <td class="text-center align-content-center">
                                    {{ $product->unit->name }}
                                </td>
                                <td class="text-center align-content-center">{{ $product->is_active ? 'Yes' : 'No' }}</td>
                                <td class="align-content-center text-center">
                                    <a href="{{ route('admin.products.show', $product->slug) }}"
                                        class="text-info mx-2 btn-sm"><i class="fa fa-eye"></i></a>
                                    <a href="{{ route('admin.products.edit', $product->slug) }}"
                                        class="text-warning mx-2 btn-sm"><i class="fa fa-edit"></i></a>
                                    <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST"
                                        class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="delete-btn text-danger bg-white border-0 btn-sm">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="offcanvas offcanvas-end" tabindex="-1" id="filterOffcanvas" aria-labelledby="filterOffcanvasLabel">
            <div class="offcanvas-header">
                <h5 id="filterOffcanvasLabel">{{ __('Filter Products') }}</h5>
                <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas"></button>
            </div>
            <div class="offcanvas-body">
                <form method="GET" action="{{ route('admin.products.index') }}">
                    <div class="mb-3">
                        <label class="form-label">{{ __('Category') }}</label>
                        <select name="category_id" id="category-select" class="form-select p-3">
                            <option value="">{{ __('All Categories') }}</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}"
                                    {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Active Status --}}
                    <div class="mb-3">
                        <label class="form-label">{{ __('Active Status') }}</label>
                        <select name="is_active" class="form-select">
                            <option value="">{{ __('All') }}</option>
                            <option value="1" {{ request('is_active') === '1' ? 'selected' : '' }}>
                                {{ __('Active') }}</option>
                            <option value="0" {{ request('is_active') === '0' ? 'selected' : '' }}>
                                {{ __('Inactive') }}</option>
                        </select>
                    </div>

                    {{-- Featured --}}
                    <div class="mb-3">
                        <label class="form-label">{{ __('Featured') }}</label>
                        <select name="is_featured" class="form-select">
                            <option value="">{{ __('All') }}</option>
                            <option value="1" {{ request('is_featured') === '1' ? 'selected' : '' }}>
                                {{ __('Featured') }}</option>
                            <option value="0" {{ request('is_featured') === '0' ? 'selected' : '' }}>
                                {{ __('Not Featured') }}</option>
                        </select>
                    </div>

                    {{-- New --}}
                    <div class="mb-3">
                        <label class="form-label">{{ __('New') }}</label>
                        <select name="is_new" class="form-select">
                            <option value="">{{ __('All') }}</option>
                            <option value="1" {{ request('is_new') === '1' ? 'selected' : '' }}>
                                {{ __('New') }}</option>
                            <option value="0" {{ request('is_new') === '0' ? 'selected' : '' }}>
                                {{ __('Not New') }}</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">{{ __('Price Range') }}</label>
                        <div class="d-flex">
                            <input type="number" name="price_min" class="form-control me-2"
                                placeholder="{{ __('Min') }}" value="{{ request('price_min') }}">
                            <input type="number" name="price_max" class="form-control"
                                placeholder="{{ __('Max') }}" value="{{ request('price_max') }}">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">{{ __('Stock Range') }}</label>
                        <div class="d-flex">
                            <input type="number" name="stock_min" class="form-control me-2"
                                placeholder="{{ __('Min') }}" value="{{ request('stock_min') }}">
                            <input type="number" name="stock_max" class="form-control"
                                placeholder="{{ __('Max') }}" value="{{ request('stock_max') }}">
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">{{ __('Apply Filters') }}</button>
                    <a href="{{ route('admin.products.index') }}"
                        class="btn btn-secondary w-100 mt-2">{{ __('Reset') }}</a>
                </form>
            </div>
        </div>
    @endsection

    @push('scripts')
        <link rel="stylesheet" href="//cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
        <script src="//cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
        <script>
            $(document).ready(function() {
                let table = $('#products-table').DataTable({
                    dom: '<"top-controls row mb-3"<"col-md-4"l><"col-md-4 text-center bulk-col"><"col-md-4"f>>rtip',
                });

                $('#bulk-action-form').appendTo('.bulk-col');

                $('#select-all').on('click', function() {
                    $('input[name="ids[]"]').prop('checked', this.checked);
                });
            });
            $(document).on('change', 'input[name="ids[]"]', function() {
                let checkedCount = $('input[name="ids[]"]:checked').length;

                if (checkedCount > 0) {
                    $('#bulk-action-select').prop('disabled', false);
                } else {
                    $('#bulk-action-select').prop('disabled', true).val('');
                    $('#bulk-apply-btn').addClass('d-none');
                }
            });

            $('#bulk-action-select').on('change', function() {
                if ($(this).val() !== '') {
                    $('#bulk-apply-btn').removeClass('d-none');
                } else {
                    $('#bulk-apply-btn').addClass('d-none');
                }
            });
            handleDeleteAjax('.delete-btn', 'Product has been deleted successfully.');
        </script>
        <script>
            $(document).ready(function() {
                // Initialize select2
                $('#category-select').select2({
                    placeholder: "{{ __('All Categories') }}",
                    allowClear: true,
                    width: '100%',
                    allowSearch: true
                });
            });
        </script>
    @endpush

@extends('dashboard.layouts.app')

@section('content')
    <div class="container-fluid py-4">
        <div class="row mb-3">
            <div class="col-6">
                <a class="btn btn-outline-secondary" href="{{ route('admin.categories.export') }}">
                    <i class="fa-solid fa-file-export"></i> {{ __('Export') }}
                </a>
            </div>
            <div class="col-6 text-end">
                <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">
                    <i class="fa fa-plus"></i> {{ __('Add Category') }}
                </a>
            </div>
        </div>

        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6>{{ __('Categories') }}</h6>
            </div>

            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div id="categories-table_length"></div>

                    <form id="bulk-action-form" action="{{ route('admin.categories.bulk') }}" method="POST" class="d-flex">
                        @csrf
                        <select name="action" id="bulk-action-select" class="form-control form-control-sm me-2" disabled
                            required>
                            <option value="">-- {{ __('Bulk Action') }} --</option>
                            <option value="delete">{{ __('Delete') }}</option>
                            <option value="activate">{{ __('Set Active') }}</option>
                            <option value="deactivate">{{ __('Set Inactive') }}</option>
                        </select>
                        <button type="submit" id="bulk-apply-btn"
                            class="btn btn-sm m-0 btn-secondary d-none">{{ __('Apply') }}</button>
                    </form>
                    <div id="categories-table_filter"></div>
                </div>
                <table id="categories-table" class="table">
                    <thead>
                        <tr>
                            <th class="text-center">
                                <input type="checkbox" id="select-all">
                            </th>
                            <th class="text-center">{{ __('ID') }}</th>
                            <th class="text-center">{{ __('Name') }}</th>
                            <th class="text-center">{{ __('Parent Category') }}</th>
                            <th class="text-center">{{ __('Image') }}</th>
                            <th class="text-center">{{ __('Active') }}</th>
                            <th class="text-center">{{ __('Sort Order') }}</th>
                            <th class="text-center">{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($categories as $category)
                            <tr>
                                <td class="text-center">
                                    <input type="checkbox" name="ids[]" value="{{ $category->id }}"
                                        form="bulk-action-form">
                                </td>
                                <td class="align-content-center text-center">{{ $category->id }}</td>
                                <td class="align-content-center text-center">
                                    {{ $category->getTranslation('name', 'en') }} , <br>
                                    {{ $category->getTranslation('name', 'ar') }}
                                </td>
                                <td class="align-content-center text-center">
                                    {{ $category->parent->getTranslation('name', 'en') }}
                                </td>
                                <td class="align-content-center text-center">
                                    <img src="{{ $category->image_url }}"
                                        alt="{{ $category->getTranslation('name', 'en') }}" class="avatar avatar-xl">
                                </td>
                                <td class="align-content-center text-center">{{ $category->is_active ? 'Yes' : 'No' }}</td>
                                <td class="align-content-center text-center">{{ $category->sort_order }}</td>
                                <td class="align-content-center text-center">
                                    <a href="{{ route('admin.categories.show', $category->slug) }}"
                                        class="text-info mx-2 btn-sm"><i class="fa fa-eye"></i></a>
                                    <a href="{{ route('admin.categories.edit', $category->slug) }}"
                                        class="text-warning mx-2 btn-sm"><i class="fa fa-edit"></i></a>
                                    <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST"
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
    </div>
@endsection

@push('scripts')
    <link rel="stylesheet" href="//cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <script src="//cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function() {
            let table = $('#categories-table').DataTable({
                dom: '<"top-controls row mb-3"<"col-md-4"l><"col-md-4 text-center bulk-col"><"col-md-4"f>>rtip',
            });

            // نحط الفورم جوه العمود اللي في النص
            $('#bulk-action-form').appendTo('.bulk-col');

            // Select/Deselect all checkboxes
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

        // لما يغير الاختيار في الـ select
        $('#bulk-action-select').on('change', function() {
            if ($(this).val() !== '') {
                $('#bulk-apply-btn').removeClass('d-none');
            } else {
                $('#bulk-apply-btn').addClass('d-none');
            }
        });
        handleDeleteAjax('.delete-btn', 'Category has been deleted successfully.');
    </script>
@endpush

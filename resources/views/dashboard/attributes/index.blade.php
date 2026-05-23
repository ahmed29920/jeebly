@extends('dashboard.layouts.app')

@section('content')
    <div class="container-fluid py-4">
        <div class="row mb-3">
            <div class="col-12 text-end">
                <a href="{{ route('admin.attributes.create') }}" class="btn btn-primary">
                    <i class="fa fa-plus"></i> Add Attribute
                </a>
            </div>
        </div>

        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6>Attributes</h6>
            </div>

            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div id="attributes-table_length"></div>

                    <form id="bulk-action-form" action="{{ route('admin.attributes.bulk') }}" method="POST" class="d-flex">
                        @csrf
                        <select name="action" id="bulk-action-select" class="form-control form-control-sm me-2" disabled
                            required>
                            <option value="">-- Bulk Action --</option>
                            <option value="delete">Delete</option>
                            <option value="activate">Set Active</option>
                            <option value="deactivate">Set Inactive</option>
                            <option value="required">Set Required</option>
                            <option value="not_required">Unset Required</option>
                            <option value="filterable">Set Filterable</option>
                            <option value="not_filterable">Unset Filterable</option>
                        </select>
                        <button type="submit" id="bulk-apply-btn"
                            class="btn btn-sm m-0 btn-secondary d-none">Apply</button>
                    </form>
                    <div id="attributes-table_filter"></div>
                </div>
                <table id="attributes-table" class="table">
                    <thead>
                        <tr>
                            <th class="text-center">
                                <input type="checkbox" id="select-all">
                            </th>
                            <th class="text-center">ID</th>
                            <th class="text-center">Name</th>
                            <th class="text-center">Code</th>
                            <th class="text-center">Type</th>
                            <th class="text-center">Active</th>
                            <th class="text-center">Required</th>
                            <th class="text-center">Filterable</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($attributes as $attribute)
                            <tr>
                                <td class="text-center">
                                    <input type="checkbox" name="ids[]" value="{{ $attribute->id }}"
                                        form="bulk-action-form">
                                </td>
                                <td class="text-center align-content-center">{{ $attribute->id }}</td>
                                <td class="text-center align-content-center">
                                    {{ $attribute->getTranslation('name', 'en') }} , <br>
                                    {{ $attribute->getTranslation('name', 'ar') }}
                                </td>
                                <td class="text-center align-content-center">{{ $attribute->code }}</td>
                                <td class="text-center align-content-center">{{ ucfirst($attribute->type) }}</td>
                                <td class="text-center align-content-center">{{ $attribute->is_active ? 'Yes' : 'No' }}
                                <td class="text-center align-content-center">{{ $attribute->is_required ? 'Yes' : 'No' }}
                                </td>
                                <td class="text-center align-content-center">{{ $attribute->is_filterable ? 'Yes' : 'No' }}
                                </td>
                                <td class="text-center align-content-center">
                                    <a href="{{ route('admin.attributes.edit', $attribute->id) }}"
                                        class="mx-2 border-0 bg-white text-warning"><i class="fa fa-edit"></i></a>
                                    <form action="{{ route('admin.attributes.destroy', $attribute->id) }}" method="POST"
                                        class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="mx-2 border-0 bg-white text-danger delete-btn"><i
                                                class="fa fa-trash"></i></button>
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
        $(function() {
            let table = $('#attributes-table').DataTable({
                dom: '<"top-controls row mb-3"<"col-md-4"l><"col-md-4 text-center bulk-col"><"col-md-4"f>>rtip',
            });

            $('#bulk-action-form').appendTo('.bulk-col');

            $('#select-all').on('click', function() {
                let rows = table.rows({
                    'search': 'applied'
                }).nodes();
                $('input[name="ids[]"]', rows).prop('checked', this.checked).trigger('change');
            });
        });

        // enable/disable bulk action
        $(document).on('change', 'input[name="ids[]"]', function() {
            let checkedCount = $('input[name="ids[]"]:checked').length;

            if (checkedCount > 0) {
                $('#bulk-action-select').prop('disabled', false);
            } else {
                $('#bulk-action-select').prop('disabled', true).val('');
                $('#bulk-apply-btn').addClass('d-none');
            }
        });

        // show apply btn only if action selected
        $('#bulk-action-select').on('change', function() {
            if ($(this).val() !== '') {
                $('#bulk-apply-btn').removeClass('d-none');
            } else {
                $('#bulk-apply-btn').addClass('d-none');
            }
        });

        handleDeleteAjax('.delete-btn', 'Attribute has been deleted successfully.');
    </script>
@endpush

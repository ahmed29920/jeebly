@extends('dashboard.layouts.app')

@section('content')
    <div class="container-fluid py-4">
        <div class="row mb-3">
            <div class="col-12 text-end">
                <a href="{{ route('admin.units.create') }}" class="btn btn-primary">
                    <i class="fa fa-plus"></i> {{ __('Add Unit') }}
                </a>
            </div>
        </div>

        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6>{{ __('Units') }}</h6>
            </div>

            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div id="units-table_length"></div>

                    <form id="bulk-action-form" action="{{ route('admin.units.bulk') }}" method="POST" class="d-flex">
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
                    <div id="units-table_filter"></div>
                </div>
                <table id="units-table" class="table">
                    <thead>
                        <tr>
                            <th class="text-center">
                                <input type="checkbox" id="select-all">
                            </th>
                            <th class="text-center">{{ __('ID') }}</th>
                            <th class="text-center">{{ __('Name') }}</th>
                            <th class="text-center">{{ __('Code') }}</th>
                            <th class="text-center">{{ __('Active') }}</th>
                            <th class="text-center">{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($units as $unit)
                            <tr>
                                <td class="text-center">
                                    <input type="checkbox" name="ids[]" value="{{ $unit->id }}"
                                        form="bulk-action-form">
                                </td>
                                <td class="align-content-center text-center">{{ $unit->id }}</td>
                                <td class="align-content-center text-center">
                                    {{ $unit->getTranslation('name', 'en') }} <br>
                                    <small class="text-muted">{{ $unit->getTranslation('name', 'ar') }}</small>
                                </td>
                                <td class="align-content-center text-center">
                                    <span class="badge bg-info">{{ is_array($unit->code) ? ($unit->code['en'] ?? '') : $unit->code }}</span>
                                    @if(is_array($unit->code) && isset($unit->code['ar']))
                                        <br><small class="text-muted">{{ $unit->code['ar'] }}</small>
                                    @endif
                                </td>
                                <td class="align-content-center text-center">
                                    @if($unit->is_active)
                                        <span class="badge bg-success">{{ __('Yes') }}</span>
                                    @else
                                        <span class="badge bg-secondary">{{ __('No') }}</span>
                                    @endif
                                </td>
                                <td class="align-content-center text-center">
                                    <a href="{{ route('admin.units.show', $unit->id) }}"
                                        class="text-info mx-2 btn-sm"><i class="fa fa-eye"></i></a>
                                    <a href="{{ route('admin.units.edit', $unit->id) }}"
                                        class="text-warning mx-2 btn-sm"><i class="fa fa-edit"></i></a>
                                    <form action="{{ route('admin.units.destroy', $unit->id) }}" method="POST"
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
            let table = $('#units-table').DataTable({
                dom: '<"top-controls row mb-3"<"col-md-4"l><"col-md-4 text-center bulk-col"><"col-md-4"f>>rtip',
            });

            // Move bulk action form to center column
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

        // When bulk action select changes
        $('#bulk-action-select').on('change', function() {
            if ($(this).val() !== '') {
                $('#bulk-apply-btn').removeClass('d-none');
            } else {
                $('#bulk-apply-btn').addClass('d-none');
            }
        });

        handleDeleteAjax('.delete-btn', 'Unit has been deleted successfully.');
    </script>
@endpush


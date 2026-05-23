@extends('dashboard.layouts.app')

@section('content')
    <div class="container-fluid py-4">
        <div class="row mb-3">
            <div class="col-12 text-end">
                <a href="{{ route('admin.coupons.create') }}" class="btn btn-primary">
                    <i class="fa fa-plus"></i> Add Coupon
                </a>
            </div>
        </div>

        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6>Coupons</h6>
            </div>

            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div id="coupons-table_length"></div>

                    <form id="bulk-action-form" action="{{ route('admin.coupons.bulk') }}" method="POST" class="d-flex">
                        @csrf
                        <select name="action" id="bulk-action-select" class="form-control form-control-sm me-2" disabled
                            required>
                            <option value="">-- Bulk Action --</option>
                            <option value="delete">Delete</option>
                            <option value="activate">Set Active</option>
                            <option value="deactivate">Set Inactive</option>
                        </select>
                        <button type="submit" id="bulk-apply-btn"
                            class="btn btn-sm m-0 btn-secondary d-none">Apply</button>
                    </form>
                    <div id="coupons-table_filter"></div>
                </div>
                <table id="coupons-table" class="table">
                    <thead>
                        <tr>
                            <th class="text-center">
                                <input type="checkbox" id="select-all">
                            </th>
                            <th class="text-center">ID</th>
                            <th class="text-center">Code</th>
                            <th class="text-center">Discount Type</th>
                            <th class="text-center">Value</th>
                            <th class="text-center">Usage Limit</th>
                            <th class="text-center">Used</th>
                            <th class="text-center">Expires At</th>
                            <th class="text-center">Active</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($coupons as $coupon)
                            <tr>
                                <td class="text-center">
                                    <input type="checkbox" name="ids[]" value="{{ $coupon->id }}"
                                        form="bulk-action-form">
                                </td>
                                <td class="align-content-center text-center">{{ $coupon->id }}</td>
                                <td class="align-content-center text-center">{{ $coupon->code }}</td>
                                <td class="align-content-center text-center">{{ ucfirst($coupon->type) }}</td>
                                <td class="align-content-center text-center">
                                    {{ $coupon->coupon_discount_value }} {{ $coupon->type === 'percent' ? '%' : currency_symbol() }}
                                </td>
                                <td class="align-content-center text-center">{{ $coupon->usage_limit }}</td>
                                <td class="align-content-center text-center">{{ $coupon->used_count }}</td>
                                <td class="align-content-center text-center">
                                    {{ $coupon->end_date ? $coupon->end_date->format('Y-m-d') : 'N/A' }}
                                </td>
                                <td class="align-content-center text-center">
                                    {{ $coupon->is_active ? 'Yes' : 'No' }}
                                </td>
                                <td class="align-content-center text-center">
                                    {{-- <a href="{{ route('admin.coupons.show', $coupon->id) }}"
                                        class="text-info mx-2 btn-sm"><i class="fa fa-eye"></i></a> --}}
                                    <a href="{{ route('admin.coupons.edit', $coupon->id) }}"
                                        class="text-warning mx-2 btn-sm"><i class="fa fa-edit"></i></a>
                                    <form action="{{ route('admin.coupons.destroy', $coupon->id) }}" method="POST"
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
            let table = $('#coupons-table').DataTable({
                dom: '<"top-controls row mb-3"<"col-md-4"l><"col-md-4 text-center bulk-col"><"col-md-4"f>>rtip',
            });

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

        $('#bulk-action-select').on('change', function() {
            if ($(this).val() !== '') {
                $('#bulk-apply-btn').removeClass('d-none');
            } else {
                $('#bulk-apply-btn').addClass('d-none');
            }
        });

        handleDeleteAjax('.delete-btn', 'Coupon has been deleted successfully.');
    </script>
@endpush

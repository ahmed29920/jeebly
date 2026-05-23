@extends('dashboard.layouts.app')

@section('content')
    <div class="container-fluid py-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6>{{ __('Reviews') }}</h6>
            </div>

            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div id="products-table_length"></div>

                    <form id="bulk-action-form" action="{{ route('admin.reviews.bulk') }}" method="POST" class="d-flex">
                        @csrf
                        <select name="action" id="bulk-action-select" class="form-control form-control-sm me-2" disabled
                            required>
                            <option value="">-- Bulk Action --</option>
                            <option value="delete">Delete</option>
                            <option value="approve">Set Approved</option>
                            <option value="reject">Set Rejected</option>
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
                            <th class="text-center">User</th>
                            <th class="text-center">Product</th>
                            <th class="text-center">Rating</th>
                            <th class="text-center">Comment</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($reviews as $review)
                            <tr>
                                <td class="text-center">
                                    <input type="checkbox" name="ids[]" value="{{ $review->id }}"
                                        form="bulk-action-form">
                                </td>
                                <td class="align-content-center text-center">{{ $review->id }}</td>
                                <td class="align-content-center text-center">
                                    <a href="">
                                        {{ $review->user->name }} , <br>
                                        {{ $review->user->email }}
                                    </a>
                                </td>
                                <td class="align-content-center text-center">
                                    @if ($review->product)
                                        <a href="{{ route('admin.products.edit', $review->product->slug) }}">
                                            <img src="{{ $review->product->images()->first()?->image_path }}"
                                                alt="{{ $review->product->getTranslation('name', 'en') }}"
                                                class="avatar avatar-xl">
                                            {{ $review->product->name }}
                                        </a>
                                    @else
                                        <span class="text-muted">{{ __('Product unavailable') }}</span>
                                    @endif
                                </td>
                                <td class="text-center align-content-center">{{ $review->rating }}</td>
                                <td class="text-center align-content-center">{{ $review->comment }}</td>
                                <td class="text-center align-content-center">
                                    @php
                                        switch ($review->status) {
                                            case 'approved':
                                                $badge = 'success';
                                                break;
                                            case 'pending':
                                                $badge = 'warning';
                                                break;
                                            case 'rejected':
                                                $badge = 'danger';
                                                break;
                                            default:
                                                $badge = 'secondary';
                                        }
                                    @endphp
                                    <span class="badge bg-{{ $badge }}">
                                        {{ ucfirst($review->status) }}
                                    </span>
                                </td>
                                <td class="align-content-center text-center">
                                    <a href="" id="edit_status" data-id="{{ $review->id }}"
                                        class="text-warning mx-2 btn-sm"><i class="fa fa-edit"></i></a>
                                    <form action="{{ route('admin.reviews.destroy', $review->id) }}"
                                        method="POST" class="d-inline">
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
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
        handleDeleteAjax('.delete-btn', 'Review has been deleted successfully.');
    </script>


    <script>
        $(document).on('click', '#edit_status', function(e) {
            e.preventDefault(); // منع الـ default link behavior

            let reviewId = $(this).data('id');

            Swal.fire({
                title: 'Change Review Status',
                input: 'select',
                inputOptions: {
                    'pending': 'Pending',
                    'approved': 'Approved',
                    'rejected': 'Rejected'
                },
                inputPlaceholder: 'Select status',
                showCancelButton: true,
                confirmButtonText: 'Update',
                cancelButtonText: 'Cancel',
            }).then((result) => {
                if (result.isConfirmed && result.value) {
                    let status = result.value;

                    let form = $('<form>', {
                        action: `/admin/reviews/${reviewId}`,
                        method: 'POST'
                    });

                    form.append('@csrf');
                    form.append('<input type="hidden" name="_method" value="PATCH">');
                    form.append('<input type="hidden" name="status" value="' + status + '">');

                    $('body').append(form);
                    form.submit();
                }
            });
        });
    </script>
@endpush

@extends('dashboard.layouts.app')

@section('content')
    <div class="container-fluid py-4">
        <div class="row mb-3">
            <div class="col-12 text-end">
                <a href="{{ route('branch.employees.create') }}" class="btn btn-primary">
                    <i class="fa fa-plus"></i> {{ __('Add Employee') }}
                </a>
            </div>
        </div>

        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6>{{ __('Employees') }}</h6>
            </div>

            <div class="card-body">
                
                <table id="employees-table" class="table">
                    <thead>
                        <tr>
                            <th class="text-center">
                                <input type="checkbox" id="select-all">
                            </th>
                            <th class="text-center">{{ __('ID') }}</th>
                            <th class="text-center">{{ __('Name') }}</th>
                            <th class="text-center">{{ __('Email') }}</th>
                            <th class="text-center">{{ __('Phone') }}</th>
                            <th class="text-center">{{ __('Branch') }}</th>
                            <th class="text-center">{{ __('Status') }}</th>
                            <th class="text-center">{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($employees as $employee)
                            <tr>
                                <td class="text-center">
                                    <input type="checkbox" name="ids[]" value="{{ $employee->id }}"
                                        form="bulk-action-form">
                                </td>
                                <td class="align-content-center text-center">{{ $employee->id }}</td>
                                <td class="align-content-center text-center">
                                    <div class="d-flex align-items-center">
                                        @if($employee->image)
                                            <img src="{{ asset('storage/' . $employee->image) }}"
                                                 class="avatar avatar-sm me-2"
                                                 alt="{{ $employee->name }}">
                                        @else
                                            <div class="avatar avatar-sm me-2 bg-gradient-secondary d-flex align-items-center justify-content-center">
                                                <i class="fa fa-user text-white"></i>
                                            </div>
                                        @endif
                                        <span>{{ $employee->name }}</span>
                                    </div>
                                </td>
                                <td class="align-content-center text-center">{{ $employee->email }}</td>
                                <td class="align-content-center text-center">{{ $employee->phone }}</td>
                                <td class="align-content-center text-center">
                                    @if($employee->branch)
                                        <span class="badge bg-info">
                                            {{ $employee->branch->getTranslation('name', 'en') }}
                                        </span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td class="align-content-center text-center">
                                    @if($employee->is_active)
                                        <span class="badge bg-success">{{ __('Active') }}</span>
                                    @else
                                        <span class="badge bg-secondary">{{ __('Inactive') }}</span>
                                    @endif
                                </td>
                                <td class="align-content-center text-center">
                                    @if ($employee->id != auth()->user()->id)
                                        <a href="{{ route('branch.employees.show', $employee->id) }}"
                                            class="text-info mx-2 btn-sm"><i class="fa fa-eye"></i></a>
                                        <a href="{{ route('branch.employees.edit', $employee->id) }}"
                                            class="text-warning mx-2 btn-sm"><i class="fa fa-edit"></i></a>
                                        <form action="{{ route('branch.employees.destroy', $employee->id) }}" method="POST"
                                            class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="delete-btn text-danger bg-white border-0 btn-sm">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </form>
                                    @else
                                        --
                                    @endif
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
            let table = $('#employees-table').DataTable({
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

        handleDeleteAjax('.delete-btn', 'Employee has been deleted successfully.');
    </script>
@endpush


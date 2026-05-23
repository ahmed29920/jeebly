@extends('dashboard.layouts.app')

@section('content')
    <div class="container-fluid py-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <h6>{{ __('Employee Details') }}</h6>
                <a href="{{ route('branch.employees.index') }}" class="btn btn-sm btn-secondary">{{ __('Back') }}</a>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-bordered">
                            <tr>
                                <th width="30%">{{ __('ID') }}</th>
                                <td>{{ $employee->id }}</td>
                            </tr>
                            <tr>
                                <th>{{ __('Name') }}</th>
                                <td>{{ $employee->name }}</td>
                            </tr>
                            <tr>
                                <th>{{ __('Email') }}</th>
                                <td>{{ $employee->email }}</td>
                            </tr>
                            <tr>
                                <th>{{ __('Phone') }}</th>
                                <td>{{ $employee->phone }}</td>
                            </tr>
                            <tr>
                                <th>{{ __('Branch') }}</th>
                                <td>
                                    @if($employee->branch)
                                        <span class="badge bg-info">
                                            {{ $employee->branch->getTranslation('name', 'en') }}
                                        </span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>{{ __('Status') }}</th>
                                <td>
                                    @if($employee->is_active)
                                        <span class="badge bg-success">{{ __('Active') }}</span>
                                    @else
                                        <span class="badge bg-secondary">{{ __('Inactive') }}</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>{{ __('Image') }}</th>
                                <td>
                                    @if($employee->image)
                                        <img src="{{ asset('storage/' . $employee->image) }}"
                                             class="img-thumbnail"
                                             style="max-width: 150px; max-height: 150px;">
                                    @else
                                        <span class="text-muted">No image</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>{{ __('Created At') }}</th>
                                <td>{{ $employee->created_at->format('Y-m-d H:i:s') }}</td>
                            </tr>
                            <tr>
                                <th>{{ __('Updated At') }}</th>
                                <td>{{ $employee->updated_at->format('Y-m-d H:i:s') }}</td>
                            </tr>
                        </table>
                    </div>
                </div>

                <div class="mt-3">
                    <a href="{{ route('branch.employees.edit', $employee->id) }}" class="btn btn-warning">
                        <i class="fa fa-edit"></i> {{ __('Edit') }}
                    </a>
                    <form action="{{ route('branch.employees.destroy', $employee->id) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="button" class="btn btn-danger delete-btn">
                            <i class="fa fa-trash"></i> {{ __('Delete') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        handleDeleteAjax('.delete-btn', 'Employee has been deleted successfully.');
    </script>
@endpush


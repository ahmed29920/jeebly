@extends('dashboard.layouts.app')

@section('content')
    <div class="container-fluid py-4">
        <div class="row mb-3">
            <div class="col-6">
            </div>
            <div class="col-6 text-end">
                <a href="{{ route('admin.branches.create') }}" class="btn btn-primary">
                    <i class="fa fa-plus"></i> {{ __('Add Branch') }}
                </a>
            </div>
        </div>

        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6>{{ __('Branches') }}</h6>
            </div>

            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div id="branches-table_length"></div>
                    <div id="branches-table_filter"></div>
                </div>
                <table id="branches-table" class="table">
                    <thead>
                        <tr>
                            <th class="text-center">{{ __('ID') }}</th>
                            <th class="text-center">{{ __('Name') }}</th>
                            <th class="text-center">{{ __('Address') }}</th>
                            <th class="text-center">{{ __('Phone') }}</th>
                            <th class="text-center">{{ __('Location') }}</th>
                            <th class="text-center">{{ __('Active') }}</th>
                            <th class="text-center">{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($branches as $branch)
                            <tr>
                                <td class="align-content-center text-center">{{ $branch->id }}</td>
                                <td class="align-content-center text-center">
                                    {{ $branch->getTranslation('name', 'en') }} <br>
                                    <small class="text-muted">{{ $branch->getTranslation('name', 'ar') }}</small>
                                </td>
                                <td class="align-content-center text-center">
                                    {{ $branch->getTranslation('address', 'en') }} <br>
                                    <small class="text-muted">{{ $branch->getTranslation('address', 'ar') }}</small>
                                </td>
                                <td class="align-content-center text-center">{{ $branch->phone ?? '-' }}</td>
                                <td class="align-content-center text-center">
                                    @if($branch->latitude && $branch->longitude)
                                        <a href="https://www.google.com/maps?q={{ $branch->latitude }},{{ $branch->longitude }}" 
                                           target="_blank" class="text-info">
                                            <i class="fa fa-map-marker-alt"></i> View Map
                                        </a>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="align-content-center text-center">
                                    @if($branch->is_active)
                                        <span class="badge bg-success">{{ __('Yes') }}</span>
                                    @else
                                        <span class="badge bg-danger">{{ __('No') }}</span>
                                    @endif
                                </td>
                                <td class="align-content-center text-center">
                                    <a href="{{ route('admin.branches.show', $branch->id) }}"
                                        class="text-info mx-2 btn-sm"><i class="fa fa-eye"></i></a>
                                    <a href="{{ route('admin.branches.edit', $branch->id) }}"
                                        class="text-warning mx-2 btn-sm"><i class="fa fa-edit"></i></a>
                                    <form action="{{ route('admin.branches.destroy', $branch->id) }}" method="POST"
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
            $('#branches-table').DataTable({
                dom: '<"top-controls row mb-3"<"col-md-6"l><"col-md-6"f>>rtip',
            });
        });
        handleDeleteAjax('.delete-btn', 'Branch has been deleted successfully.');
    </script>
@endpush


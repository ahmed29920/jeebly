@extends('dashboard.layouts.app')

@section('content')
    <div class="container-fluid py-4">
        <div class="row mb-3">
            <div class="col-6">
            </div>
            <div class="col-6 text-end">
                <a href="{{ route('admin.variants.create') }}" class="btn btn-primary">
                    <i class="fa fa-plus"></i> {{ __('Add Variant') }}
                </a>
            </div>
        </div>

        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6>{{ __('Variants') }}</h6>
            </div>

            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div id="variants-table_length"></div>
                    <div id="variants-table_filter"></div>
                </div>
                <table id="variants-table" class="table">
                    <thead>
                        <tr>
                            <th class="text-center">{{ __('ID') }}</th>
                            <th class="text-center">{{ __('Name') }}</th>
                            <th class="text-center">{{ __('Type') }}</th>
                            <th class="text-center">{{ __('Required') }}</th>
                            <th class="text-center">{{ __('Active') }}</th>
                            <th class="text-center">{{ __('Options Count') }}</th>
                            <th class="text-center">{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($variants as $variant)
                            <tr>
                                <td class="align-content-center text-center">{{ $variant->id }}</td>
                                <td class="align-content-center text-center">
                                    {{ $variant->getTranslation('name', 'en') }} <br>
                                    <small class="text-muted">{{ $variant->getTranslation('name', 'ar') }}</small>
                                </td>
                                <td class="align-content-center text-center">
                                    <span class="badge bg-info">{{ ucfirst($variant->type) }}</span>
                                </td>
                                <td class="align-content-center text-center">
                                    @if($variant->is_required)
                                        <span class="badge bg-success">{{ __('Yes') }}</span>
                                    @else
                                        <span class="badge bg-secondary">{{ __('No') }}</span>
                                    @endif
                                </td>
                                <td class="align-content-center text-center">
                                    @if($variant->is_active)
                                        <span class="badge bg-success">{{ __('Yes') }}</span>
                                    @else
                                        <span class="badge bg-danger">{{ __('No') }}</span>
                                    @endif
                                </td>
                                <td class="align-content-center text-center">
                                    {{ $variant->options->count() }}
                                </td>
                                <td class="align-content-center text-center">
                                    <a href="{{ route('admin.variants.show', $variant->id) }}"
                                        class="text-info mx-2 btn-sm"><i class="fa fa-eye"></i></a>
                                    <a href="{{ route('admin.variants.edit', $variant->id) }}"
                                        class="text-warning mx-2 btn-sm"><i class="fa fa-edit"></i></a>
                                    <form action="{{ route('admin.variants.destroy', $variant->id) }}" method="POST"
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
            $('#variants-table').DataTable({
                dom: '<"top-controls row mb-3"<"col-md-6"l><"col-md-6"f>>rtip',
            });
        });
        handleDeleteAjax('.delete-btn', 'Variant has been deleted successfully.');
    </script>
@endpush


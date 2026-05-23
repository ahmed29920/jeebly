@extends('dashboard.layouts.app')

@section('content')
    <div class="container-fluid py-4">
        <div class="row mb-3">
            <div class="col-12 d-flex justify-content-end">
                <a href="{{ route('admin.zones.create') }}" class="btn btn-primary">
                    <i class="fa fa-plus"></i> {{ __('Add Zone') }}
                </a>
            </div>
        </div>

        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="mb-0">{{ __('Zones') }}</h6>
            </div>

            <div class="card-body">
                <form method="GET" action="{{ route('admin.zones.index') }}" class="row g-2 align-items-end mb-3">
                    <div class="col-md-5">
                        <label class="form-label">{{ __('Search') }}</label>
                        <input type="text" name="search" class="form-control" value="{{ $filters['search'] ?? '' }}"
                            placeholder="{{ __('Search by name...') }}">
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">{{ __('Status') }}</label>
                        <select name="is_active" class="form-control">
                            <option value="">{{ __('All') }}</option>
                            <option value="1" {{ ($filters['is_active'] ?? '') === '1' ? 'selected' : '' }}>
                                {{ __('Active') }}
                            </option>
                            <option value="0" {{ ($filters['is_active'] ?? '') === '0' ? 'selected' : '' }}>
                                {{ __('Inactive') }}
                            </option>
                        </select>
                    </div>

                    <div class="col-md-2">
                        <label class="form-label">{{ __('Per page') }}</label>
                        <select name="per_page" class="form-control">
                            @foreach ([15, 25, 50, 100] as $pp)
                                <option value="{{ $pp }}"
                                    {{ (int) request('per_page', 15) === (int) $pp ? 'selected' : '' }}>
                                    {{ $pp }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-2 d-flex gap-2">
                        <button class="btn btn-secondary w-100" type="submit">
                            <i class="fa fa-filter"></i> {{ __('Filter') }}
                        </button>
                        <a class="btn btn-outline-secondary w-100" href="{{ route('admin.zones.index') }}">
                            {{ __('Reset') }}
                        </a>
                    </div>
                </form>

                <div class="table-responsive">
                    <table class="table align-items-center mb-0">
                        <thead>
                            <tr>
                                <th class="text-center">{{ __('ID') }}</th>
                                <th>{{ __('Name') }}</th>
                                <th class="text-center">{{ __('Points') }}</th>
                                <th class="text-center">{{ __('Active') }}</th>
                                <th class="text-center">{{ __('Created') }}</th>
                                <th class="text-center">{{ __('Actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($zones as $zone)
                                <tr>
                                    <td class="text-center align-middle">{{ $zone->id }}</td>
                                    <td class="align-middle">{{ $zone->name }}</td>
                                    <td class="text-center align-middle">
                                        <span class="badge bg-info">{{ is_array($zone->polygon) ? count($zone->polygon) : 0 }}</span>
                                    </td>
                                    <td class="text-center align-middle">
                                        @if ($zone->is_active)
                                            <span class="badge bg-success">{{ __('Yes') }}</span>
                                        @else
                                            <span class="badge bg-secondary">{{ __('No') }}</span>
                                        @endif
                                    </td>
                                    <td class="text-center align-middle">
                                        {{ optional($zone->created_at)->format('Y-m-d') }}
                                    </td>
                                    <td class="text-center align-middle">
                                        <a href="{{ route('admin.zones.show', $zone) }}" class="text-info mx-2 btn-sm">
                                            <i class="fa fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.zones.edit', $zone) }}" class="text-warning mx-2 btn-sm">
                                            <i class="fa fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.zones.destroy', $zone) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="delete-btn text-danger bg-white border-0 btn-sm">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4 text-muted">
                                        {{ __('No zones found.') }}
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    {{ $zones->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        handleDeleteAjax('.delete-btn', 'Zone has been deleted successfully.');
    </script>
@endpush


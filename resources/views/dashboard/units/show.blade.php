@extends('dashboard.layouts.app')

@section('content')
    <div class="container-fluid py-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <h6>{{ __('Unit Details') }}</h6>
                <a href="{{ route('admin.units.index') }}" class="btn btn-sm btn-secondary">{{ __('Back') }}</a>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-bordered">
                            <tr>
                                <th width="30%">{{ __('ID') }}</th>
                                <td>{{ $unit->id }}</td>
                            </tr>
                            <tr>
                                <th>{{ __('Name') }} (EN)</th>
                                <td>{{ $unit->getTranslation('name', 'en') }}</td>
                            </tr>
                            <tr>
                                <th>{{ __('Name') }} (AR)</th>
                                <td>{{ $unit->getTranslation('name', 'ar') }}</td>
                            </tr>
                            <tr>
                                <th>{{ __('Code') }} (EN)</th>
                                <td><span class="badge bg-info">{{ is_array($unit->code) ? ($unit->code['en'] ?? '') : $unit->code }}</span></td>
                            </tr>
                            <tr>
                                <th>{{ __('Code') }} (AR)</th>
                                <td><span class="badge bg-info">{{ is_array($unit->code) ? ($unit->code['ar'] ?? '') : '' }}</span></td>
                            </tr>
                            <tr>
                                <th>{{ __('Active') }}</th>
                                <td>
                                    @if($unit->is_active)
                                        <span class="badge bg-success">{{ __('Yes') }}</span>
                                    @else
                                        <span class="badge bg-secondary">{{ __('No') }}</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>{{ __('Created At') }}</th>
                                <td>{{ $unit->created_at->format('Y-m-d H:i:s') }}</td>
                            </tr>
                            <tr>
                                <th>{{ __('Updated At') }}</th>
                                <td>{{ $unit->updated_at->format('Y-m-d H:i:s') }}</td>
                            </tr>
                        </table>
                    </div>
                </div>

                <div class="mt-3">
                    <a href="{{ route('admin.units.edit', $unit->id) }}" class="btn btn-warning">
                        <i class="fa fa-edit"></i> {{ __('Edit') }}
                    </a>
                    <form action="{{ route('admin.units.destroy', $unit->id) }}" method="POST" class="d-inline">
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
        handleDeleteAjax('.delete-btn', 'Unit has been deleted successfully.');
    </script>
@endpush


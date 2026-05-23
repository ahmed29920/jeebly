@extends('dashboard.layouts.app')

@section('content')
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-lg-11 mx-auto">
                {{-- Header Card --}}
                <div class="card border-0 shadow-sm mb-4 rounded-4 bg-white" style="background: #ffffff !important; border-color: transparent !important;">
                    <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center py-3" style="background: #ffffff !important;">
                        <div>
                            <h5 class="mb-0 fw-bold">
                                <i class="fas fa-truck me-2 text-primary"></i>
                                {{ __('Delivery') }} #{{ $delivery->id }} — {{ $delivery->user->name }}
                            </h5>
                            <small class="text-muted">{{ __('Detailed information about the delivery driver') }}</small>
                        </div>
                        <div>
                            @if($delivery->is_online)
                                <span class="badge bg-gradient-success px-3 py-2 text-uppercase shadow-sm">
                                    <i class="fas fa-circle me-1"></i>{{ __('Online') }}
                                </span>
                            @else
                                <span class="badge bg-gradient-secondary px-3 py-2 text-uppercase shadow-sm">
                                    <i class="fas fa-circle me-1"></i>{{ __('Offline') }}
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Personal Information --}}
                <div class="card border-0 shadow-sm mb-4 rounded-4 bg-white" style="background: #ffffff !important; border-color: transparent !important;">
                    <div class="card-header bg-white border-0 py-3" style="background: #ffffff !important;">
                        <h6 class="mb-0 fw-bold">
                            <i class="fas fa-user me-2 text-primary"></i>
                            {{ __('Personal Information') }}
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="row g-4">
                            <div class="col-md-6">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="icon-shape icon-sm bg-gradient-primary text-white rounded-circle me-3 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                        <i class="fas fa-id-card"></i>
                                    </div>
                                    <div>
                                        <small class="text-muted d-block mb-1">{{ __('ID') }}</small>
                                        <h6 class="mb-0 fw-bold">#{{ $delivery->id }}</h6>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="icon-shape icon-sm bg-gradient-info text-white rounded-circle me-3 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                        <i class="fas fa-user"></i>
                                    </div>
                                    <div>
                                        <small class="text-muted d-block mb-1">{{ __('Name') }}</small>
                                        <h6 class="mb-0 fw-bold">{{ $delivery->user->name }}</h6>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="icon-shape icon-sm bg-gradient-success text-white rounded-circle me-3 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                        <i class="fas fa-envelope"></i>
                                    </div>
                                    <div>
                                        <small class="text-muted d-block mb-1">{{ __('Email') }}</small>
                                        <h6 class="mb-0 fw-bold">
                                            <a href="mailto:{{ $delivery->user->email }}" class="text-decoration-none">
                                                {{ $delivery->user->email }}
                                            </a>
                                        </h6>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="icon-shape icon-sm bg-gradient-warning text-white rounded-circle me-3 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                        <i class="fas fa-phone"></i>
                                    </div>
                                    <div>
                                        <small class="text-muted d-block mb-1">{{ __('Phone') }}</small>
                                        <h6 class="mb-0 fw-bold">
                                            <a href="tel:{{ $delivery->user->phone }}" class="text-decoration-none">
                                                {{ $delivery->user->phone }}
                                            </a>
                                        </h6>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="icon-shape icon-sm bg-gradient-danger text-white rounded-circle me-3 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                        <i class="fas fa-store"></i>
                                    </div>
                                    <div>
                                        <small class="text-muted d-block mb-1">{{ __('Branch') }}</small>
                                        <h6 class="mb-0">
                                            <span class="badge bg-gradient-info px-3 py-2">
                                                {{ $delivery->branch->name }}
                                            </span>
                                        </h6>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="icon-shape icon-sm bg-gradient-dark text-white rounded-circle me-3 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                        <i class="fas fa-wallet"></i>
                                    </div>
                                    <div>
                                        <small class="text-muted d-block mb-1">{{ __('Wallet') }}</small>
                                        <h6 class="mb-0 fw-bold text-success">
                                            {{ format_currency($delivery->wallet) }}
                                        </h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Vehicle Information --}}
                <div class="card border-0 shadow-sm mb-4 rounded-4 bg-white" style="background: #ffffff !important; border-color: transparent !important;">
                    <div class="card-header bg-white border-0 py-3" style="background: #ffffff !important;">
                        <h6 class="mb-0 fw-bold">
                            <i class="fas fa-car me-2 text-primary"></i>
                            {{ __('Vehicle Information') }}
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="row g-4">
                            <div class="col-md-6">
                                <div class="p-3 bg-light rounded-3 mb-3">
                                    <small class="text-muted d-block mb-2">{{ __('Plate Number') }}</small>
                                    <h6 class="mb-0 fw-bold">
                                        <span class="badge bg-gradient-primary px-3 py-2 fs-6">
                                            {{ $delivery->plate_number }}
                                        </span>
                                    </h6>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="p-3 bg-light rounded-3 mb-3">
                                    <small class="text-muted d-block mb-2">{{ __('Vehicle Name') }}</small>
                                    <h6 class="mb-0 fw-bold">{{ $delivery->vehicle_name }}</h6>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="p-3 bg-light rounded-3 mb-3">
                                    <small class="text-muted d-block mb-2">{{ __('Vehicle Type') }}</small>
                                    <h6 class="mb-0">
                                        <span class="badge bg-gradient-info px-3 py-2">
                                            {{ $delivery->vehicle_type }}
                                        </span>
                                    </h6>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="p-3 bg-light rounded-3 mb-3">
                                    <small class="text-muted d-block mb-2">{{ __('Vehicle Color') }}</small>
                                    <h6 class="mb-0">
                                        <span class="badge bg-gradient-secondary px-3 py-2">
                                            {{ $delivery->vehicle_color }}
                                        </span>
                                    </h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Timestamps --}}
                <div class="card border-0 shadow-sm mb-4 rounded-4 bg-white" style="background: #ffffff !important; border-color: transparent !important;">
                    <div class="card-header bg-white border-0 py-3" style="background: #ffffff !important;">
                        <h6 class="mb-0 fw-bold">
                            <i class="fas fa-clock me-2 text-primary"></i>
                            {{ __('Timestamps') }}
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="row g-4">
                            <div class="col-md-6">
                                <div class="d-flex align-items-center">
                                    <div class="icon-shape icon-sm bg-gradient-primary text-white rounded-circle me-3 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                        <i class="fas fa-calendar-plus"></i>
                                    </div>
                                    <div>
                                        <small class="text-muted d-block mb-1">{{ __('Created At') }}</small>
                                        <h6 class="mb-0 fw-bold">{{ $delivery->created_at->format('d M Y, h:i A') }}</h6>
                                        <small class="text-muted">{{ $delivery->created_at->diffForHumans() }}</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex align-items-center">
                                    <div class="icon-shape icon-sm bg-gradient-info text-white rounded-circle me-3 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                        <i class="fas fa-calendar-check"></i>
                                    </div>
                                    <div>
                                        <small class="text-muted d-block mb-1">{{ __('Updated At') }}</small>
                                        <h6 class="mb-0 fw-bold">{{ $delivery->updated_at->format('d M Y, h:i A') }}</h6>
                                        <small class="text-muted">{{ $delivery->updated_at->diffForHumans() }}</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Actions --}}
                <div class="card border-0 shadow-sm rounded-4 bg-white" style="background: #ffffff !important; border-color: transparent !important;">
                    <div class="card-body" style="background: #ffffff !important;">
                        <div class="d-flex justify-content-between align-items-center">
                            <a href="{{ route('branch.deliveries.index') }}" class="btn btn-secondary shadow-sm">
                                <i class="fas fa-arrow-left me-2"></i>{{ __('Back to List') }}
                            </a>
                            <div>
                                <a href="{{ route('branch.deliveries.edit', $delivery->id) }}" class="btn btn-warning shadow-sm me-2">
                                    <i class="fas fa-edit me-2"></i>{{ __('Edit') }}
                                </a>
                                <form action="{{ route('branch.deliveries.destroy', $delivery->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn btn-danger shadow-sm delete-btn">
                                        <i class="fas fa-trash me-2"></i>{{ __('Delete') }}
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        handleDeleteAjax('.delete-btn', 'Delivery has been deleted successfully.');
    </script>
@endpush


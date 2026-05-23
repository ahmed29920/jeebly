@extends('dashboard.layouts.app')

@section('content')
    <div class="container-fluid py-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <h6>{{ __('Slider Details') }}</h6>
                <div>
                    <a href="{{ route('admin.sliders.edit', $slider->id) }}" class="btn btn-sm btn-warning">
                        <i class="fa fa-edit"></i> {{ __('Edit') }}
                    </a>
                    <a href="{{ route('admin.sliders.index') }}" class="btn btn-sm btn-secondary">{{ __('Back') }}</a>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-4">
                            <h6 class="text-muted mb-2">{{ __('Status') }}</h6>
                            @if($slider->is_active)
                                <span class="badge bg-success">{{ __('Active') }}</span>
                            @else
                                <span class="badge bg-danger">{{ __('Inactive') }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-4">
                            <h6 class="text-muted mb-2">{{ __('Created At') }}</h6>
                            <p>{{ $slider->created_at->format('Y-m-d H:i:s') }}</p>
                        </div>
                    </div>
                </div>

                <hr>

                <div class="row">
                    <div class="col-md-12">
                        <div class="mb-4">
                            <h6 class="text-muted mb-3">{{ __('Slider Image') }}</h6>
                            @if($slider->image_url)
                                <div class="text-center">
                                    <img src="{{ $slider->image_url }}" alt="Slider Image" 
                                        class="img-fluid img-thumbnail" 
                                        style="max-width: 100%; max-height: 500px; object-fit: contain;">
                                </div>
                                <div class="mt-3 text-center">
                                    <a href="{{ $slider->image_url }}" target="_blank" class="btn btn-sm btn-info">
                                        <i class="fa fa-external-link-alt"></i> {{ __('View Full Size') }}
                                    </a>
                                </div>
                            @else
                                <p class="text-muted">{{ __('No image available') }}</p>
                            @endif
                        </div>
                    </div>
                </div>

                <hr>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-4">
                            <h6 class="text-muted mb-2">{{ __('Updated At') }}</h6>
                            <p>{{ $slider->updated_at->format('Y-m-d H:i:s') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


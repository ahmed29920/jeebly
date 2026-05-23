@extends('dashboard.layouts.app')

@section('content')
    <div class="container-fluid py-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <h6>{{ __('Variant Details') }}</h6>
                <div>
                    <a href="{{ route('admin.variants.edit', $variant->id) }}" class="btn btn-sm btn-warning">
                        <i class="fa fa-edit"></i> {{ __('Edit') }}
                    </a>
                    <a href="{{ route('admin.variants.index') }}" class="btn btn-sm btn-secondary">{{ __('Back') }}</a>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-4">
                            <h6 class="text-muted mb-2">{{ __('Name') }}</h6>
                            <p class="mb-1"><strong>{{ __('English') }}:</strong> {{ $variant->getTranslation('name', 'en') }}</p>
                            <p class="mb-0"><strong>{{ __('Arabic') }}:</strong> {{ $variant->getTranslation('name', 'ar') }}</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-4">
                            <h6 class="text-muted mb-2">{{ __('Type') }}</h6>
                            <p><span class="badge bg-info">{{ ucfirst($variant->type) }}</span></p>
                        </div>
                    </div>
                </div>

                <hr>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-4">
                            <h6 class="text-muted mb-2">{{ __('Required') }}</h6>
                            @if($variant->is_required)
                                <span class="badge bg-success">{{ __('Yes') }}</span>
                            @else
                                <span class="badge bg-secondary">{{ __('No') }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-4">
                            <h6 class="text-muted mb-2">{{ __('Status') }}</h6>
                            @if($variant->is_active)
                                <span class="badge bg-success">{{ __('Active') }}</span>
                            @else
                                <span class="badge bg-danger">{{ __('Inactive') }}</span>
                            @endif
                        </div>
                    </div>
                </div>

                @if(in_array($variant->type, ['select', 'radio', 'checkbox']) && $variant->options->count() > 0)
                    <hr>
                    <div class="row">
                        <div class="col-md-12">
                            <h6 class="text-muted mb-3">{{ __('Options') }}</h6>
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>{{ __('Code') }}</th>
                                            <th>{{ __('Name (EN)') }}</th>
                                            <th>{{ __('Name (AR)') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($variant->options as $option)
                                            <tr>
                                                <td><code>{{ $option->code }}</code></td>
                                                <td>{{ $option->getTranslation('name', 'en') }}</td>
                                                <td>{{ $option->getTranslation('name', 'ar') }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @endif

                <hr>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-4">
                            <h6 class="text-muted mb-2">{{ __('Created At') }}</h6>
                            <p>{{ $variant->created_at->format('Y-m-d H:i:s') }}</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-4">
                            <h6 class="text-muted mb-2">{{ __('Updated At') }}</h6>
                            <p>{{ $variant->updated_at->format('Y-m-d H:i:s') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


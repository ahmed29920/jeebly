@extends('dashboard.layouts.app')

@section('content')
    <div class="container-fluid py-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <h6>{{ __('Edit Branch') }}</h6>
                <a href="{{ route('admin.branches.index') }}" class="btn btn-sm btn-secondary">{{ __('Back') }}</a>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.branches.update', $branch->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label>{{ __('Name') }} (EN) <span class="text-danger">*</span></label>
                                <input type="text" name="name[en]" id="name_en" 
                                    class="form-control @error('name.en') is-invalid @enderror" 
                                    value="{{ old('name.en', $branch->getTranslation('name', 'en')) }}" required>
                                @error('name.en')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label>{{ __('Name') }} (AR) <span class="text-danger">*</span></label>
                                <input type="text" name="name[ar]" 
                                    class="form-control @error('name.ar') is-invalid @enderror" 
                                    value="{{ old('name.ar', $branch->getTranslation('name', 'ar')) }}" required>
                                @error('name.ar')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label>{{ __('Slug') }} <span class="text-danger">*</span></label>
                                <input type="text" name="slug" id="slug" 
                                    class="form-control @error('slug') is-invalid @enderror" 
                                    value="{{ old('slug', $branch->slug) }}" required>
                                @error('slug')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">Auto-generated from English name if left empty</small>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label>{{ __('Address') }} (EN)</label>
                                <textarea name="address[en]" class="form-control @error('address.en') is-invalid @enderror" 
                                    rows="3">{{ old('address.en', $branch->getTranslation('address', 'en')) }}</textarea>
                                @error('address.en')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label>{{ __('Address') }} (AR)</label>
                                <textarea name="address[ar]" class="form-control @error('address.ar') is-invalid @enderror" 
                                    rows="3">{{ old('address.ar', $branch->getTranslation('address', 'ar')) }}</textarea>
                                @error('address.ar')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label>{{ __('Phone') }}</label>
                                <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror" 
                                    value="{{ old('phone', $branch->phone) }}">
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label>{{ __('Latitude') }}</label>
                                <input type="number" step="any" name="latitude" id="latitude" 
                                    class="form-control @error('latitude') is-invalid @enderror" 
                                    value="{{ old('latitude', $branch->latitude) }}">
                                @error('latitude')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label>{{ __('Longitude') }}</label>
                                <input type="number" step="any" name="longitude" id="longitude" 
                                    class="form-control @error('longitude') is-invalid @enderror" 
                                    value="{{ old('longitude', $branch->longitude) }}">
                                @error('longitude')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-check pt-3">
                                <input class="form-check-input border" type="checkbox" name="is_active" id="is_active"
                                    value="1" {{ old('is_active', $branch->is_active) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">
                                    {{ __('Active') }}
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4">
                        <button class="btn btn-primary" type="submit">{{ __('Update') }}</button>
                        <a href="{{ route('admin.branches.index') }}" class="btn btn-secondary">{{ __('Cancel') }}</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Generate slug from name[en]
        const nameInput = document.querySelector('input[name="name[en]"]');
        const slugInput = document.querySelector('input[name="slug"]');

        nameInput.addEventListener('input', function() {
            if (!slugInput.dataset.manual) {
                let slug = this.value.toLowerCase()
                    .replace(/ /g, '-')
                    .replace(/[^\w-]+/g, ''); // remove special characters
                slugInput.value = slug;
            }
        });

        // Allow manual slug editing
        slugInput.addEventListener('input', function() {
            this.dataset.manual = 'true';
        });
    </script>
@endpush


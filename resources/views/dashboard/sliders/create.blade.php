@extends('dashboard.layouts.app')

@section('content')
    <div class="container-fluid py-4">
        <div class="card">
            <div class="card-header">
                <h6>{{ __('Add Slider') }}</h6>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.sliders.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="row">
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label>{{ __('Image') }} <span class="text-danger">*</span></label>
                                <input type="file" name="image" id="image" 
                                    class="form-control @error('image') is-invalid @enderror" 
                                    accept="image/jpeg,image/jpg,image/png,image/webp" required>
                                @error('image')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">
                                    {{ __('Accepted formats: JPG, JPEG, PNG, WEBP. Max size: 2MB') }}
                                </small>
                                <div class="mt-2">
                                    <img id="preview_image" src="" alt="Image Preview" 
                                        class="img-thumbnail" style="max-width: 400px; max-height: 300px; display: none;">
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-check pt-3">
                                <input class="form-check-input border" type="checkbox" name="is_active" id="is_active"
                                    value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">
                                    {{ __('Active') }}
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4">
                        <button class="btn btn-primary" type="submit">{{ __('Save') }}</button>
                        <a href="{{ route('admin.sliders.index') }}" class="btn btn-secondary">{{ __('Cancel') }}</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Image preview
        document.getElementById('image').addEventListener('change', function(e) {
            const [file] = this.files;
            if (file) {
                const preview = document.getElementById('preview_image');
                preview.src = URL.createObjectURL(file);
                preview.style.display = 'block';
            } else {
                document.getElementById('preview_image').style.display = 'none';
            }
        });
    </script>
@endpush


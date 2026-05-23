@extends('dashboard.layouts.app')

@section('content')
    <div class="container-fluid py-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <h6>{{ __('Edit Slider') }}</h6>
                <a href="{{ route('admin.sliders.index') }}" class="btn btn-sm btn-secondary">{{ __('Back') }}</a>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.sliders.update', $slider->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label>{{ __('Image') }}</label>
                                <input type="file" name="image" id="image" 
                                    class="form-control @error('image') is-invalid @enderror" 
                                    accept="image/jpeg,image/jpg,image/png,image/webp">
                                @error('image')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">
                                    {{ __('Accepted formats: JPG, JPEG, PNG, WEBP. Max size: 2MB') }}
                                    <br>{{ __('Leave empty to keep current image') }}
                                </small>
                                
                                @if($slider->image_url)
                                    <div class="mt-3">
                                        <label>{{ __('Current Image') }}:</label>
                                        <div class="mt-2">
                                            <img src="{{ $slider->image_url }}" alt="Current Image" 
                                                id="current_image" class="img-thumbnail" 
                                                style="max-width: 400px; max-height: 300px;">
                                        </div>
                                    </div>
                                @endif
                                
                                <div class="mt-2">
                                    <img id="preview_image" src="" alt="New Image Preview" 
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
                                    value="1" {{ old('is_active', $slider->is_active) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">
                                    {{ __('Active') }}
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4">
                        <button class="btn btn-primary" type="submit">{{ __('Update') }}</button>
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
            const preview = document.getElementById('preview_image');
            const currentImage = document.getElementById('current_image');
            
            if (file) {
                preview.src = URL.createObjectURL(file);
                preview.style.display = 'block';
                if (currentImage) {
                    currentImage.style.display = 'none';
                }
            } else {
                preview.style.display = 'none';
                if (currentImage) {
                    currentImage.style.display = 'block';
                }
            }
        });
    </script>
@endpush


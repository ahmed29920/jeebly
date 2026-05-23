@extends('dashboard.layouts.app')

@section('content')
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-lg-11 mx-auto">
                <div class="card border-0 shadow-sm mb-4 rounded-4 bg-white" style="background: #ffffff !important; border-color: transparent !important;">
                    <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center py-3" style="background: #ffffff !important;">
                        <div>
                            <h5 class="mb-0 fw-bold">
                                <i class="fas fa-edit me-2 text-primary"></i>
                                {{ __('Edit Delivery') }}
                            </h5>
                            <small class="text-muted">{{ __('Update delivery driver information') }}</small>
                        </div>
                        <a href="{{ route('branch.deliveries.index') }}" class="btn btn-sm btn-secondary">
                            <i class="fas fa-arrow-left me-1"></i>{{ __('Back') }}
                        </a>
                    </div>
                    <div class="card-body" style="background: #ffffff !important;">
                        @php
                            $currentBranch = auth()->user()?->branch;
                        @endphp
                        <form action="{{ route('branch.deliveries.update', $delivery->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            {{-- Personal Information Section --}}
                            <div class="mb-4">
                                <h6 class="fw-bold text-secondary mb-3">
                                    <i class="fas fa-user me-2 text-primary"></i>
                                    {{ __('Personal Information') }}
                                </h6>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">
                                            <i class="fas fa-user text-primary me-2"></i>{{ __('Name') }} <span class="text-danger">*</span>
                                        </label>
                                        <input type="text" name="name"
                                               class="form-control shadow-sm @error('name') is-invalid @enderror"
                                               value="{{ old('name', $delivery->user->name) }}" required>
                                        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">
                                            <i class="fas fa-phone text-primary me-2"></i>{{ __('Phone') }} <span class="text-danger">*</span>
                                        </label>
                                        <input type="text" name="phone"
                                               class="form-control shadow-sm @error('phone') is-invalid @enderror"
                                               value="{{ old('phone', $delivery->user->phone) }}" required>
                                        @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">
                                            <i class="fas fa-envelope text-primary me-2"></i>{{ __('Email') }} <span class="text-muted">({{ __('Optional') }})</span>
                                        </label>
                                        <input type="email" name="email"
                                               class="form-control shadow-sm @error('email') is-invalid @enderror"
                                               value="{{ old('email', $delivery->user->email) }}">
                                        @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">
                                            <i class="fas fa-lock text-primary me-2"></i>{{ __('Password') }}
                                        </label>
                                        <input type="password" name="password"
                                               class="form-control shadow-sm @error('password') is-invalid @enderror"
                                               placeholder="Leave empty to keep current">
                                        @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                </div>
                            </div>

                            <hr class="my-4">

                            {{-- Vehicle Information Section --}}
                            <div class="mb-4">
                                <h6 class="fw-bold text-secondary mb-3">
                                    <i class="fas fa-car me-2 text-primary"></i>
                                    {{ __('Vehicle Information') }}
                                </h6>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">
                                            <i class="fas fa-id-card text-primary me-2"></i>{{ __('Plate Number') }} <span class="text-danger">*</span>
                                        </label>
                                        <input type="text" name="plate_number"
                                               class="form-control shadow-sm @error('plate_number') is-invalid @enderror"
                                               value="{{ old('plate_number', $delivery->plate_number) }}" required>
                                        @error('plate_number') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">
                                            <i class="fas fa-tag text-primary me-2"></i>{{ __('Vehicle Type') }} <span class="text-danger">*</span>
                                        </label>
                                        <input type="text" name="vehicle_type"
                                               class="form-control shadow-sm @error('vehicle_type') is-invalid @enderror"
                                               value="{{ old('vehicle_type', $delivery->vehicle_type) }}" required>
                                        @error('vehicle_type') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">
                                            <i class="fas fa-car-side text-primary me-2"></i>{{ __('Vehicle Name') }} <span class="text-danger">*</span>
                                        </label>
                                        <input type="text" name="vehicle_name"
                                               class="form-control shadow-sm @error('vehicle_name') is-invalid @enderror"
                                               value="{{ old('vehicle_name', $delivery->vehicle_name) }}" required>
                                        @error('vehicle_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">
                                            <i class="fas fa-palette text-primary me-2"></i>{{ __('Vehicle Color') }} <span class="text-danger">*</span>
                                        </label>
                                        <input type="text" name="vehicle_color"
                                               class="form-control shadow-sm @error('vehicle_color') is-invalid @enderror"
                                               value="{{ old('vehicle_color', $delivery->vehicle_color) }}" required>
                                        @error('vehicle_color') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                </div>
                            </div>

                            <hr class="my-4">

                            {{-- Additional Information Section --}}
                            <div class="mb-4">
                                <h6 class="fw-bold text-secondary mb-3">
                                    <i class="fas fa-info-circle me-2 text-primary"></i>
                                    {{ __('Additional Information') }}
                                </h6>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">
                                            <i class="fas fa-wallet text-primary me-2"></i>{{ __('Wallet') }} <span class="text-danger">*</span>
                                        </label>
                                        <input type="number" name="wallet" step="0.01"
                                               class="form-control shadow-sm @error('wallet') is-invalid @enderror"
                                               value="{{ old('wallet', $delivery->wallet) }}" required>
                                        @error('wallet') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">
                                            <i class="fas fa-store text-primary me-2"></i>{{ __('Branch') }}
                                        </label>
                                        <input type="text" class="form-control shadow-sm"
                                               value="{{ $currentBranch?->name ?? __('Current Branch') }}" disabled>
                                        <input type="hidden" name="branch_id" value="{{ $currentBranch?->id }}">
                                        <small class="text-muted d-block mt-1">{{ __('Delivery belongs to your current branch.') }}</small>
                                        @error('branch_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">
                                            <i class="fas fa-toggle-on text-primary me-2"></i>{{ __('Status') }} <span class="text-danger">*</span>
                                        </label>
                                        <select name="is_online"
                                                class="form-select shadow-sm @error('is_online') is-invalid @enderror" required>
                                            <option value="1" {{ old('is_online', $delivery->is_online) == 1 ? 'selected' : '' }}>
                                                {{ __('Online') }}
                                            </option>
                                            <option value="0" {{ old('is_online', $delivery->is_online) == 0 ? 'selected' : '' }}>
                                                {{ __('Offline') }}
                                            </option>
                                        </select>
                                        @error('is_online') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                </div>
                            </div>

                            <hr class="my-4">

                            {{-- Documents Section --}}
                            <div class="mb-4">
                                <h6 class="fw-bold text-secondary mb-3">
                                    <i class="fas fa-file-upload me-2 text-primary"></i>
                                    {{ __('Documents') }}
                                </h6>
                                <div class="row g-3">
                                    <div class="col-md-12">
                                        {{-- Existing Documents --}}
                                        @if($delivery->documents && count($delivery->documents) > 0)
                                            <div class="mb-3">
                                                <label class="form-label fw-semibold">{{ __('Existing Documents') }}</label>
                                                <div class="d-flex flex-wrap gap-3">
                                                    @foreach($delivery->documents as $document)
                                                        <div class="position-relative border rounded p-2" style="width: 150px; height: 150px;">
                                                            <img src="{{ asset('storage/' . $document) }}" class="rounded" style="width:100%;height:100%;object-fit:cover;">
                                                            <span class="badge bg-info position-absolute top-0 start-0 m-1">{{ __('Current') }}</span>
                                                        </div>
                                                    @endforeach
                                                </div>
                                                <small class="text-muted d-block mt-2">{{ __('Upload new documents to replace existing ones') }}</small>
                                            </div>
                                        @endif

                                        <label class="form-label fw-semibold">
                                            <i class="fas fa-file-image text-primary me-2"></i>{{ __('Upload Documents') }}
                                            <small class="text-muted">(Images: jpeg, png, jpg, gif, webp - Max: 2MB each)</small>
                                        </label>
                                        <input type="file" name="documents[]" multiple
                                               class="form-control shadow-sm @error('documents') is-invalid @enderror @error('documents.*') is-invalid @enderror"
                                               accept="image/jpeg,image/png,image/jpg,image/gif,image/webp"
                                               id="documentsInput">
                                        @error('documents') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                        @error('documents.*') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                        <div id="documentsPreview" class="d-flex flex-wrap gap-3 mt-3"></div>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-4 pt-3 border-top">
                                <button type="submit" class="btn btn-primary shadow-sm px-4">
                                    <i class="fas fa-save me-2"></i>{{ __('Update') }}
                                </button>
                                <a href="{{ route('branch.deliveries.index') }}" class="btn btn-secondary shadow-sm px-4">
                                    <i class="fas fa-times me-2"></i>{{ __('Cancel') }}
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Documents preview
        document.getElementById('documentsInput').addEventListener('change', function(e) {
            const previewContainer = document.getElementById('documentsPreview');
            previewContainer.innerHTML = '';

            const files = Array.from(e.target.files);

            files.forEach((file, index) => {
                if (file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const div = document.createElement('div');
                        div.className = 'position-relative border rounded p-2';
                        div.style.width = '150px';
                        div.style.height = '150px';
                        div.innerHTML = `
                            <img src="${e.target.result}" class="rounded" style="width:100%;height:100%;object-fit:cover;">
                            <button type="button" class="btn btn-sm btn-danger position-absolute top-0 end-0 remove-document" data-index="${index}" style="width:25px;height:25px;padding:0;font-size:12px;border-radius:50%;">×</button>
                        `;
                        previewContainer.appendChild(div);

                        // Remove button functionality
                        div.querySelector('.remove-document').addEventListener('click', function() {
                            div.remove();
                            // Create new FileList without this file
                            const dt = new DataTransfer();
                            Array.from(e.target.files).forEach((f, i) => {
                                if (i !== index) dt.items.add(f);
                            });
                            document.getElementById('documentsInput').files = dt.files;
                        });
                    };
                    reader.readAsDataURL(file);
                }
            });
        });
    </script>
@endpush


@extends('dashboard.layouts.app')

@section('content')
    <div class="container-fluid py-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <h6>{{ __('Edit Variant') }}</h6>
                <a href="{{ route('admin.variants.index') }}" class="btn btn-sm btn-secondary">{{ __('Back') }}</a>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.variants.update', $variant->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label>{{ __('Name') }} (EN) <span class="text-danger">*</span></label>
                                <input type="text" name="name[en]" id="name_en"
                                    class="form-control @error('name.en') is-invalid @enderror"
                                    value="{{ old('name.en', $variant->getTranslation('name', 'en')) }}" required>
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
                                    value="{{ old('name.ar', $variant->getTranslation('name', 'ar')) }}" required>
                                @error('name.ar')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label>{{ __('Type') }} <span class="text-danger">*</span></label>
                                <select name="type" id="type"
                                    class="form-control @error('type') is-invalid @enderror" required>
                                    <option value="text" {{ old('type', $variant->type) == 'text' ? 'selected' : '' }}>{{ __('Text') }}</option>
                                    <option value="number" {{ old('type', $variant->type) == 'number' ? 'selected' : '' }}>{{ __('Number') }}</option>
                                    <option value="select" {{ old('type', $variant->type) == 'select' ? 'selected' : '' }}>{{ __('Select') }}</option>
                                    <option value="radio" {{ old('type', $variant->type) == 'radio' ? 'selected' : '' }}>{{ __('Radio') }}</option>
                                    <option value="checkbox" {{ old('type', $variant->type) == 'checkbox' ? 'selected' : '' }}>{{ __('Checkbox') }}</option>
                                </select>
                                @error('type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-check pt-3">
                                <input class="form-check-input border" type="checkbox" name="is_required" id="is_required"
                                    value="1" {{ old('is_required', $variant->is_required) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_required">
                                    {{ __('Required') }}
                                </label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-check pt-3">
                                <input class="form-check-input border" type="checkbox" name="is_active" id="is_active"
                                    value="1" {{ old('is_active', $variant->is_active) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">
                                    {{ __('Active') }}
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Options Container (shown only for select, radio, checkbox types) -->
                    <div class="mt-4" id="options-container"
                         style="display: {{ in_array($variant->type, ['select', 'radio', 'checkbox']) ? 'block' : 'none' }};">
                        <h6 class="mb-3">{{ __('Options') }}</h6>
                        <div id="options-list">
                            @php
                                $oldOptions = old('options', []);
                                $existingOptions = $variant->options ?? collect();
                                $hasOldOptions = !empty($oldOptions);
                                $optionsToShow = $hasOldOptions ? $oldOptions : $existingOptions;
                            @endphp

                            @if($hasOldOptions)
                                @foreach($oldOptions as $i => $option)
                                    <div class="card mb-3 option-item">
                                        <div class="card-body">
                                            <div class="row">
                                                <input type="hidden" name="options[{{ $i }}][id]" value="{{ $option['id'] ?? '' }}">
                                                <div class="col-md-4">
                                                    <div class="mb-3">
                                                        <label>{{ __('Name') }} (EN) <span class="text-danger">*</span></label>
                                                        <input type="text" name="options[{{ $i }}][name][en]"
                                                            class="form-control @error('options.' . $i . '.name.en') is-invalid @enderror"
                                                            value="{{ $option['name']['en'] ?? '' }}" required>
                                                        @error('options.' . $i . '.name.en')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="mb-3">
                                                        <label>{{ __('Name') }} (AR) <span class="text-danger">*</span></label>
                                                        <input type="text" name="options[{{ $i }}][name][ar]"
                                                            class="form-control @error('options.' . $i . '.name.ar') is-invalid @enderror"
                                                            value="{{ $option['name']['ar'] ?? '' }}" required>
                                                        @error('options.' . $i . '.name.ar')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="mb-3">
                                                        <label>{{ __('Code') }} <span class="text-danger">*</span></label>
                                                        <input type="text" name="options[{{ $i }}][code]"
                                                            class="form-control @error('options.' . $i . '.code') is-invalid @enderror"
                                                            value="{{ $option['code'] ?? '' }}" required>
                                                        @error('options.' . $i . '.code')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-md-1">
                                                    <label>&nbsp;</label>
                                                    <button type="button" class="btn btn-danger btn-sm remove-option w-100">
                                                        <i class="fa fa-trash"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                @foreach($existingOptions as $i => $option)
                                    <div class="card mb-3 option-item">
                                        <div class="card-body">
                                            <div class="row">
                                                <input type="hidden" name="options[{{ $i }}][id]" value="{{ $option->id }}">
                                                <div class="col-md-4">
                                                    <div class="mb-3">
                                                        <label>{{ __('Name') }} (EN) <span class="text-danger">*</span></label>
                                                        <input type="text" name="options[{{ $i }}][name][en]"
                                                            class="form-control @error('options.' . $i . '.name.en') is-invalid @enderror"
                                                            value="{{ $option->getTranslation('name', 'en') }}" required>
                                                        @error('options.' . $i . '.name.en')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="mb-3">
                                                        <label>{{ __('Name') }} (AR) <span class="text-danger">*</span></label>
                                                        <input type="text" name="options[{{ $i }}][name][ar]"
                                                            class="form-control @error('options.' . $i . '.name.ar') is-invalid @enderror"
                                                            value="{{ $option->getTranslation('name', 'ar') }}" required>
                                                        @error('options.' . $i . '.name.ar')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="mb-3">
                                                        <label>{{ __('Code') }} <span class="text-danger">*</span></label>
                                                        <input type="text" name="options[{{ $i }}][code]"
                                                            class="form-control @error('options.' . $i . '.code') is-invalid @enderror"
                                                            value="{{ $option->code }}" required>
                                                        @error('options.' . $i . '.code')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-md-1">
                                                    <label>&nbsp;</label>
                                                    <button type="button" class="btn btn-danger btn-sm remove-option w-100">
                                                        <i class="fa fa-trash"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                        <button type="button" class="btn btn-sm btn-secondary" id="add-option">
                            <i class="fa fa-plus"></i> {{ __('Add Option') }}
                        </button>
                    </div>

                    <div class="mt-4">
                        <button class="btn btn-primary" type="submit">{{ __('Update') }}</button>
                        <a href="{{ route('admin.variants.index') }}" class="btn btn-secondary">{{ __('Cancel') }}</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            let optionIndex = {{ max(count(old('options', [])), count($variant->options ?? [])) }};

            // Show/hide options container based on type
            function toggleOptionsContainer() {
                const type = $('#type').val();
                if (type === 'select' || type === 'radio' || type === 'checkbox') {
                    $('#options-container').show();
                } else {
                    $('#options-container').hide();
                    if (confirm('{{ __('Changing the type will remove all options. Continue?') }}')) {
                        $('#options-list').empty();
                        optionIndex = 0;
                    } else {
                        // Revert to previous value
                        $('#type').val('{{ $variant->type }}');
                        toggleOptionsContainer();
                    }
                }
            }

            // Handle type change
            $('#type').on('change', function() {
                toggleOptionsContainer();
            });

            // Add new option
            $('#add-option').on('click', function() {
                const optionHtml = `
                    <div class="card mb-3 option-item">
                        <div class="card-body">
                            <div class="row">
                                <input type="hidden" name="options[${optionIndex}][id]" value="">
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label>{{ __('Name') }} (EN) <span class="text-danger">*</span></label>
                                        <input type="text" name="options[${optionIndex}][name][en]"
                                            class="form-control" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label>{{ __('Name') }} (AR) <span class="text-danger">*</span></label>
                                        <input type="text" name="options[${optionIndex}][name][ar]"
                                            class="form-control" required>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="mb-3">
                                        <label>{{ __('Code') }} <span class="text-danger">*</span></label>
                                        <input type="text" name="options[${optionIndex}][code]"
                                            class="form-control" required>
                                    </div>
                                </div>
                                <div class="col-md-1">
                                    <label>&nbsp;</label>
                                    <button type="button" class="btn btn-danger btn-sm remove-option w-100">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
                $('#options-list').append(optionHtml);
                optionIndex++;
            });

            // Remove option
            $(document).on('click', '.remove-option', function() {
                $(this).closest('.option-item').remove();
            });
        });
    </script>
@endpush


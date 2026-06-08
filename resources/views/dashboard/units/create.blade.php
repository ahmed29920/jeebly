@extends('dashboard.layouts.app')

@section('content')
    <div class="container-fluid py-4">
        <div class="card">
            <div class="card-header">
                <h6>{{ __('Add Unit') }}</h6>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.units.store') }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label>{{ __('Name') }} (EN) <span class="text-danger">*</span></label>
                                <input type="text" name="name[en]" class="form-control" value="{{ old('name.en') }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label>{{ __('Name') }} (AR) <span class="text-danger">*</span></label>
                                <input type="text" name="name[ar]" class="form-control" value="{{ old('name.ar') }}" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label>{{ __('Code') }} (EN) <span class="text-danger">*</span></label>
                                <input type="text" name="code[en]" class="form-control" value="{{ old('code.en') }}" required
                                    placeholder="e.g., KG, L, PCS">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label>{{ __('Code') }} (AR) <span class="text-danger">*</span></label>
                                <input type="text" name="code[ar]" class="form-control" value="{{ old('code.ar') }}" required
                                    placeholder="e.g., كجم, لتر, قطعة">
                            </div>
                        </div>
                    </div>
                    <small class="text-muted">Unit code in both languages (e.g., KG/كجم, L/لتر, PCS/قطعة)</small>

                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="is_active" id="is_active"
                                value="1" @checked(old('is_active', true))>
                            <label class="form-check-label" for="is_active">
                                {{ __('Active') }}
                            </label>
                        </div>
                    </div>

                    <button class="btn btn-primary mt-3" type="submit">{{ __('Save') }}</button>
                    <a href="{{ route('admin.units.index') }}" class="btn btn-secondary mt-3">{{ __('Cancel') }}</a>
                </form>
            </div>
        </div>
    </div>
@endsection

@extends('dashboard.layouts.app')

@section('content')
    <div class="container-fluid py-4">
        <div class="card">
            <div class="card-header">
                <h6>{{ __('Add Employee') }}</h6>
            </div>
            <div class="card-body">
                @php
                    $currentBranch = auth()->user()?->branch;
                @endphp
                <form action="{{ route('branch.employees.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label>{{ __('Name') }} <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label>{{ __('Email') }} <span class="text-muted">({{ __('Optional') }})</span></label>
                                <input type="email" name="email" class="form-control" value="{{ old('email') }}">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label>{{ __('Phone') }} <span class="text-danger">*</span></label>
                                <input type="text" name="phone" class="form-control" value="{{ old('phone') }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label>{{ __('Branch') }}</label>
                                <input type="text" class="form-control" value="{{ $currentBranch?->getTranslation('name','en') ?? __('Current Branch') }}" disabled>
                                <input type="hidden" name="branch_id" value="{{ $currentBranch?->id }}">
                                <small class="text-muted">{{ __('Employee will be added to your current branch.') }}</small>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label>{{ __('Password') }} <span class="text-danger">*</span></label>
                                <input type="password" name="password" class="form-control" required minlength="6">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label>{{ __('Image') }}</label>
                                <input type="file" name="image" class="form-control" accept="image/*">
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="is_active" id="is_active"
                                value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">
                                {{ __('Active') }}
                            </label>
                        </div>
                    </div>

                    <button class="btn btn-primary mt-3" type="submit">{{ __('Save') }}</button>
                    <a href="{{ route('admin.employees.index') }}" class="btn btn-secondary mt-3">{{ __('Cancel') }}</a>
                </form>
            </div>
        </div>
    </div>
@endsection


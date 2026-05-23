@extends('dashboard.layouts.app')

@section('content')
    <div class="container-fluid py-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <h6>{{ __('Edit Employee') }}</h6>
                <a href="{{ route('admin.employees.index') }}" class="btn btn-sm btn-secondary">{{ __('Back') }}</a>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.employees.update', $employee->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label>{{ __('Name') }} <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control"
                                    value="{{ old('name', $employee->name) }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label>{{ __('Email') }} <span class="text-muted">({{ __('Optional') }})</span></label>
                                <input type="email" name="email" class="form-control"
                                    value="{{ old('email', $employee->email) }}">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label>{{ __('Phone') }} <span class="text-danger">*</span></label>
                                <input type="text" name="phone" class="form-control"
                                    value="{{ old('phone', $employee->phone) }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label>{{ __('Branch') }} <span class="text-danger">*</span></label>
                                <select name="branch_id" class="form-control" required>
                                    <option value="">-- {{ __('Select Branch') }} --</option>
                                    @foreach($branches as $branch)
                                        <option value="{{ $branch->id }}"
                                            {{ old('branch_id', $employee->branch_id) == $branch->id ? 'selected' : '' }}>
                                            {{ $branch->getTranslation('name', 'en') }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label>{{ __('Password') }}</label>
                                <input type="password" name="password" class="form-control" minlength="6">
                                <small class="text-muted">Leave blank to keep current password</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label>{{ __('Image') }}</label>
                                @if($employee->image)
                                    <div class="mb-2">
                                        <img src="{{ asset('storage/' . $employee->image) }}"
                                             class="img-thumbnail"
                                             style="max-width: 100px; max-height: 100px;">
                                    </div>
                                @endif
                                <input type="file" name="image" class="form-control" accept="image/*">
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="is_active" id="is_active"
                                value="1" {{ old('is_active', $employee->is_active) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">
                                {{ __('Active') }}
                            </label>
                        </div>
                    </div>

                    <button class="btn btn-primary mt-3" type="submit">{{ __('Update') }}</button>
                    <a href="{{ route('admin.employees.index') }}" class="btn btn-secondary mt-3">{{ __('Cancel') }}</a>
                </form>
            </div>
        </div>
    </div>
@endsection


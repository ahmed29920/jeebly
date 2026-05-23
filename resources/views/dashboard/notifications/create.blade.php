@extends('dashboard.layouts.app')

@php
    $page = 'notifications';
@endphp

@section('title', __('Send Notification'))

@section('content')
    <div class="container-fluid p-4 p-lg-4">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <nav aria-label="breadcrumb" class="mb-2">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a></li>
                        <li class="breadcrumb-item active">{{ __('Send Notification') }}</li>
                    </ol>
                </nav>
                <h1 class="h3 mb-0">{{ __('Send Notification') }}</h1>
                <p class="text-muted mb-0">{{ __('Send a push notification (Firebase) and save it in the database.') }}</p>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">{{ __('Notification Details') }}</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('admin.notifications.store') }}">
                            @csrf

                            <div class="mb-4">
                                <label class="form-label">{{ __('Audience') }} *</label>
                                <div class="d-flex flex-wrap gap-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="audience" id="audience_all" value="all"
                                            {{ old('audience', 'selected') === 'all' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="audience_all">{{ __('All users') }}</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="audience" id="audience_selected" value="selected"
                                            {{ old('audience', 'selected') === 'selected' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="audience_selected">{{ __('Selected users') }}</label>
                                    </div>
                                </div>
                                @error('audience')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4" id="selected-users-wrapper">
                                <label class="form-label">{{ __('Users') }}</label>
                                <p class="text-muted small mb-2">{{ __('Choose one or more users to notify.') }}</p>
                                <input
                                    type="text"
                                    id="users-search"
                                    class="form-control mb-2"
                                    placeholder="{{ __('Search by name or phone...') }}"
                                    autocomplete="off"
                                >
                                <select name="user_ids[]" class="form-select" multiple id="users-select">
                                    @foreach(($users ?? collect()) as $user)
                                        @php
                                            $label = trim(($user->name ?? '') . ' - ' . ($user->phone ?? $user->email ?? ''));
                                        @endphp
                                        <option value="{{ $user->id }}"
                                            data-search="{{ mb_strtolower(($user->name ?? '') . ' ' . ($user->phone ?? '') . ' ' . ($user->email ?? ''), 'UTF-8') }}"
                                            {{ in_array($user->id, old('user_ids', []), true) ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('user_ids')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                                @error('user_ids.*')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="title" class="form-label">{{ __('Title') }} *</label>
                                <input id="title" name="title" type="text"
                                    class="form-control @error('title') is-invalid @enderror"
                                    value="{{ old('title') }}" required maxlength="150"
                                    placeholder="{{ __('e.g. New offer available') }}">
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="body" class="form-label">{{ __('Body') }} *</label>
                                <textarea id="body" name="body" rows="4"
                                    class="form-control @error('body') is-invalid @enderror"
                                    required maxlength="1000"
                                    placeholder="{{ __('Write the notification message...') }}">{{ old('body') }}</textarea>
                                @error('body')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="data_json" class="form-label">{{ __('Extra data (JSON)') }}</label>
                                <p class="text-muted small mb-2">
                                    {{ __('Optional. This will be sent to the app as key/value pairs. Must be valid JSON.') }}
                                </p>
                                <textarea id="data_json" name="data_json" rows="5"
                                    class="form-control @error('data_json') is-invalid @enderror"
                                    placeholder='{"type":"promo","id":123}'>{{ old('data_json') }}</textarea>
                                @error('data_json')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="d-flex justify-content-between align-items-center">
                                <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary">{{ __('Cancel') }}</a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fa-solid fa-paper-plane me-2"></i>{{ __('Send') }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0"><i class="fa-solid fa-circle-info me-2"></i>{{ __('Notes') }}</h5>
                    </div>
                    <div class="card-body">
                        <ul class="list-unstyled mb-0">
                            <li class="mb-2"><strong>{{ __('Database') }}:</strong> {{ __('Saved to the user notifications list.') }}</li>
                            <li class="mb-2"><strong>{{ __('Firebase') }}:</strong> {{ __('Sent only to users with an FCM token.') }}</li>
                            <li class="mb-2"><strong>{{ __('Audience') }}:</strong> {{ __('When sending to all users, this may take some time.') }}</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const all = document.getElementById('audience_all');
        const selected = document.getElementById('audience_selected');
        const wrapper = document.getElementById('selected-users-wrapper');
        const searchInput = document.getElementById('users-search');
        const usersSelect = document.getElementById('users-select');

        function update() {
            const isSelected = selected && selected.checked;
            if (wrapper) wrapper.style.display = isSelected ? '' : 'none';
        }

        function normalize(str) {
            return (str || '').toString().toLowerCase().trim();
        }

        function filterUsers() {
            if (!usersSelect || !searchInput) return;
            const q = normalize(searchInput.value);

            Array.from(usersSelect.options).forEach(opt => {
                const hay = normalize(opt.dataset.search || opt.text);
                const match = q === '' || hay.includes(q);
                opt.hidden = !match;
            });
        }

        if (all) all.addEventListener('change', update);
        if (selected) selected.addEventListener('change', update);
        if (searchInput) searchInput.addEventListener('input', filterUsers);
        update();
        filterUsers();
    });
</script>
@endpush


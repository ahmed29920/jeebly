@extends('dashboard.layouts.app')

@php
    $page = 'notifications';
    $oldRecipient = old('recipient_type', 'users');
    $oldAudience = old('audience', 'selected');
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
                <p class="text-muted mb-0">{{ __('Send FCM push and/or in-app notifications to users, branch staff, or deliveries.') }}</p>
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
                                <label class="form-label">{{ __('Recipient type') }} *</label>
                                <div class="d-flex flex-wrap gap-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="recipient_type" id="type_users" value="users"
                                            {{ $oldRecipient === 'users' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="type_users">{{ __('Users') }}</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="recipient_type" id="type_branches" value="branches"
                                            {{ $oldRecipient === 'branches' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="type_branches">{{ __('Branches') }}</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="recipient_type" id="type_deliveries" value="deliveries"
                                            {{ $oldRecipient === 'deliveries' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="type_deliveries">{{ __('Deliveries') }}</label>
                                    </div>
                                </div>
                                @error('recipient_type')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label class="form-label">{{ __('Audience') }} *</label>
                                <div class="d-flex flex-wrap gap-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="audience" id="audience_all" value="all"
                                            {{ $oldAudience === 'all' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="audience_all" id="audience_all_label">{{ __('All users') }}</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="audience" id="audience_selected" value="selected"
                                            {{ $oldAudience === 'selected' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="audience_selected" id="audience_selected_label">{{ __('Selected users') }}</label>
                                    </div>
                                </div>
                                @error('audience')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4 selection-wrapper" id="selected-users-wrapper" data-type="users">
                                <label class="form-label">{{ __('Users') }}</label>
                                <p class="text-muted small mb-2">{{ __('Choose one or more users to notify.') }}</p>
                                <input type="text" id="users-search" class="form-control mb-2"
                                    placeholder="{{ __('Search by name or phone...') }}" autocomplete="off">
                                <select name="user_ids[]" class="form-select" multiple id="users-select" size="8">
                                    @foreach(($users ?? collect()) as $user)
                                        @php
                                            $label = trim(($user->name ?? '') . ' - ' . ($user->phone ?? $user->email ?? ''));
                                        @endphp
                                        <option value="{{ $user->id }}"
                                            data-search="{{ mb_strtolower(($user->name ?? '') . ' ' . ($user->phone ?? '') . ' ' . ($user->email ?? ''), 'UTF-8') }}"
                                            {{ in_array($user->id, old('user_ids', []), false) ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('user_ids')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4 selection-wrapper" id="selected-branches-wrapper" data-type="branches">
                                <label class="form-label">{{ __('Branches') }}</label>
                                <p class="text-muted small mb-2">{{ __('Notify all employees of the selected branches.') }}</p>
                                <input type="text" id="branches-search" class="form-control mb-2"
                                    placeholder="{{ __('Search branches...') }}" autocomplete="off">
                                <select name="branch_ids[]" class="form-select" multiple id="branches-select" size="8">
                                    @foreach(($branches ?? collect()) as $branch)
                                        @php
                                            $branchName = $branch->getTranslation('name', app()->getLocale())
                                                ?: $branch->getTranslation('name', 'en');
                                        @endphp
                                        <option value="{{ $branch->id }}"
                                            data-search="{{ mb_strtolower($branchName, 'UTF-8') }}"
                                            {{ in_array($branch->id, old('branch_ids', []), false) ? 'selected' : '' }}>
                                            {{ $branchName }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('branch_ids')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4 selection-wrapper" id="selected-deliveries-wrapper" data-type="deliveries">
                                <label class="form-label">{{ __('Deliveries') }}</label>
                                <p class="text-muted small mb-2">{{ __('Choose one or more delivery drivers to notify.') }}</p>
                                <input type="text" id="deliveries-search" class="form-control mb-2"
                                    placeholder="{{ __('Search by name or phone...') }}" autocomplete="off">
                                <select name="delivery_ids[]" class="form-select" multiple id="deliveries-select" size="8">
                                    @foreach(($deliveries ?? collect()) as $delivery)
                                        @php
                                            $dUser = $delivery->user;
                                            $label = trim(($dUser->name ?? '') . ' - ' . ($dUser->phone ?? $dUser->email ?? '') . ' (#' . $delivery->id . ')');
                                        @endphp
                                        <option value="{{ $delivery->id }}"
                                            data-search="{{ mb_strtolower(($dUser->name ?? '') . ' ' . ($dUser->phone ?? '') . ' ' . ($dUser->email ?? ''), 'UTF-8') }}"
                                            {{ in_array($delivery->id, old('delivery_ids', []), false) ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('delivery_ids')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label class="form-label">{{ __('Channels') }} *</label>
                                <div class="d-flex flex-wrap gap-4">
                                    <div class="form-check">
                                        <input type="hidden" name="send_in_app" value="0">
                                        <input class="form-check-input" type="checkbox" name="send_in_app" id="send_in_app" value="1"
                                            {{ (string) old('send_in_app', '1') === '1' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="send_in_app">{{ __('In-app notification') }}</label>
                                    </div>
                                    <div class="form-check">
                                        <input type="hidden" name="send_push" value="0">
                                        <input class="form-check-input" type="checkbox" name="send_push" id="send_push" value="1"
                                            {{ (string) old('send_push', '1') === '1' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="send_push">{{ __('FCM push') }}</label>
                                    </div>
                                </div>
                                @error('send_in_app')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                                @error('send_push')
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
                            <li class="mb-2"><strong>{{ __('Users') }}:</strong> {{ __('App customers.') }}</li>
                            <li class="mb-2"><strong>{{ __('Branches') }}:</strong> {{ __('Employees of the selected branches.') }}</li>
                            <li class="mb-2"><strong>{{ __('Deliveries') }}:</strong> {{ __('Delivery drivers.') }}</li>
                            <li class="mb-2"><strong>{{ __('In-app') }}:</strong> {{ __('Saved to the recipients notifications list.') }}</li>
                            <li class="mb-2"><strong>{{ __('FCM') }}:</strong> {{ __('Sent only to recipients with an FCM token.') }}</li>
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
        const typeInputs = document.querySelectorAll('input[name="recipient_type"]');
        const audienceAll = document.getElementById('audience_all');
        const audienceSelected = document.getElementById('audience_selected');
        const allLabel = document.getElementById('audience_all_label');
        const selectedLabel = document.getElementById('audience_selected_label');
        const wrappers = document.querySelectorAll('.selection-wrapper');

        const labels = {
            users: {
                all: @json(__('All users')),
                selected: @json(__('Selected users')),
            },
            branches: {
                all: @json(__('All branches')),
                selected: @json(__('Selected branches')),
            },
            deliveries: {
                all: @json(__('All deliveries')),
                selected: @json(__('Selected deliveries')),
            },
        };

        function currentType() {
            const checked = document.querySelector('input[name="recipient_type"]:checked');
            return checked ? checked.value : 'users';
        }

        function updateUi() {
            const type = currentType();
            const isSelected = audienceSelected && audienceSelected.checked;

            if (allLabel && labels[type]) allLabel.textContent = labels[type].all;
            if (selectedLabel && labels[type]) selectedLabel.textContent = labels[type].selected;

            wrappers.forEach(wrapper => {
                const match = wrapper.dataset.type === type && isSelected;
                wrapper.style.display = match ? '' : 'none';
            });
        }

        function normalize(str) {
            return (str || '').toString().toLowerCase().trim();
        }

        function bindSearch(inputId, selectId) {
            const searchInput = document.getElementById(inputId);
            const select = document.getElementById(selectId);
            if (!searchInput || !select) return;

            searchInput.addEventListener('input', function () {
                const q = normalize(searchInput.value);
                Array.from(select.options).forEach(opt => {
                    const hay = normalize(opt.dataset.search || opt.text);
                    opt.hidden = !(q === '' || hay.includes(q));
                });
            });
        }

        typeInputs.forEach(input => input.addEventListener('change', updateUi));
        if (audienceAll) audienceAll.addEventListener('change', updateUi);
        if (audienceSelected) audienceSelected.addEventListener('change', updateUi);

        bindSearch('users-search', 'users-select');
        bindSearch('branches-search', 'branches-select');
        bindSearch('deliveries-search', 'deliveries-select');

        updateUi();
    });
</script>
@endpush

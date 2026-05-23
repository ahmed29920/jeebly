@extends('dashboard.layouts.app')

@section('content')
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-lg-11 mx-auto">
                <div class="card border-0 shadow-sm mb-4 rounded-4 bg-white" style="background: #ffffff !important; border-color: transparent !important;">
                    <div class="card-header bg-white border-0 py-3" style="background: #ffffff !important;">
                        <div>
                            <h5 class="mb-0 fw-bold">
                                <i class="fas fa-cog me-2 text-primary"></i>
                                {{ __('Settings') }}
                            </h5>
                            <small class="text-muted">{{ __('Manage application settings and configuration') }}</small>
                        </div>
                    </div>
                    <div class="card-body" style="background: #ffffff !important;">
                        <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            {{-- General Settings Section --}}
                            <div class="mb-4">
                                <h6 class="fw-bold text-secondary mb-3">
                                    <i class="fas fa-info-circle me-2 text-primary"></i>
                                    {{ __('General Settings') }}
                                </h6>
                                <div class="row g-3">
                                    <div class="col-md-12">
                                        <label class="form-label fw-semibold">
                                            <i class="fas fa-tag text-primary me-2"></i>{{ __('App Name') }}
                                        </label>
                                        <input type="text" name="app_name"
                                               class="form-control shadow-sm"
                                               value="{{ old('app_name', $settings['app_name'] ?? '') }}"
                                               placeholder="{{ __('Enter application name') }}">
                                    </div>
                                </div>
                            </div>

                            <hr class="my-4">

                            {{-- Branding Section --}}
                            <div class="mb-4">
                                <h6 class="fw-bold text-secondary mb-3">
                                    <i class="fas fa-palette me-2 text-primary"></i>
                                    {{ __('Branding') }}
                                </h6>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">
                                            <i class="fas fa-image text-primary me-2"></i>{{ __('App Logo') }}
                                        </label>
                                        <input type="file" name="app_logo"
                                               class="form-control shadow-sm"
                                               accept="image/*"
                                               id="logoInput">
                                        @if (!empty($settings['app_logo']))
                                            <div class="mt-3">
                                                <label class="form-label fw-semibold">{{ __('Current Logo') }}</label>
                                                <div class="border rounded p-2 bg-light" style="max-width: 200px;">
                                                    <img src="{{ asset('storage/' . $settings['app_logo']) }}"
                                                         id="review_logo"
                                                         alt="logo"
                                                         class="img-fluid rounded">
                                                </div>
                                            </div>
                                        @endif
                                        <div id="logoPreview" class="mt-3"></div>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">
                                            <i class="fas fa-icons text-primary me-2"></i>{{ __('App Icon') }}
                                        </label>
                                        <input type="file" name="app_icon"
                                               class="form-control shadow-sm"
                                               accept="image/*"
                                               id="iconInput">
                                        @if (!empty($settings['app_icon']))
                                            <div class="mt-3">
                                                <label class="form-label fw-semibold">{{ __('Current Icon') }}</label>
                                                <div class="border rounded p-2 bg-light" style="max-width: 100px;">
                                                    <img src="{{ asset('storage/' . $settings['app_icon']) }}"
                                                         id="review_icon"
                                                         alt="icon"
                                                         class="img-fluid rounded">
                                                </div>
                                            </div>
                                        @endif
                                        <div id="iconPreview" class="mt-3"></div>
                                    </div>
                                </div>
                            </div>

                            <hr class="my-4">

                            {{-- Shipping Settings Section --}}
                            <div class="mb-4">
                                <h6 class="fw-bold text-secondary mb-3">
                                    <i class="fas fa-shipping-fast me-2 text-primary"></i>
                                    {{ __('Shipping Settings') }}
                                </h6>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">
                                            <i class="fas fa-route text-primary me-2"></i>{{ __('Cost of Shipping for KM') }}
                                        </label>
                                        <input type="number" name="shipping_cost" step="0.01" min="0"
                                               class="form-control shadow-sm"
                                               value="{{ old('shipping_cost', $settings['shipping_cost'] ?? '') }}"
                                               placeholder="0.00">
                                        <small class="text-muted d-block mt-1">{{ __('Cost per kilometer') }}</small>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">
                                            <i class="fas fa-dollar-sign text-primary me-2"></i>{{ __('Min Shipping Cost') }}
                                        </label>
                                        <input type="number" name="min_shipping_cost" step="0.01" min="0"
                                               class="form-control shadow-sm"
                                               value="{{ old('min_shipping_cost', $settings['min_shipping_cost'] ?? '') }}"
                                               placeholder="0.00">
                                        <small class="text-muted d-block mt-1">{{ __('Minimum shipping cost regardless of distance') }}</small>
                                    </div>
                                </div>
                            </div>

                            <hr class="my-4">

                            {{-- Delivery Man Settings Section --}}
                            <div class="mb-4">
                                <h6 class="fw-bold text-secondary mb-3">
                                    <i class="fas fa-truck me-2 text-primary"></i>
                                    {{ __('Delivery Man Settings') }}
                                </h6>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">
                                            <i class="fas fa-calculator text-primary me-2"></i>{{ __('Calculation Method') }}
                                        </label>
                                        <select name="delivery_man_calculation_method"
                                                class="form-select shadow-sm"
                                                id="delivery_man_calculation_method">
                                            <option value="percentage" {{ old('delivery_man_calculation_method', $settings['delivery_man_calculation_method'] ?? 'percentage') == 'percentage' ? 'selected' : '' }}>
                                                {{ __('Percentage of Total Order') }}
                                            </option>
                                            <option value="fixed" {{ old('delivery_man_calculation_method', $settings['delivery_man_calculation_method'] ?? '') == 'fixed' ? 'selected' : '' }}>
                                                {{ __('Fixed Number') }}
                                            </option>
                                        </select>
                                        <small class="text-muted d-block mt-1">{{ __('Choose how to calculate delivery man payment') }}</small>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">
                                            <i class="fas fa-hashtag text-primary me-2"></i>{{ __('Calculation Value') }}
                                        </label>
                                        <input type="number"
                                               name="delivery_man_calculation_value"
                                               step="0.01"
                                               min="0"
                                               class="form-control shadow-sm"
                                               value="{{ old('delivery_man_calculation_value', $settings['delivery_man_calculation_value'] ?? '') }}"
                                               placeholder="0.00"
                                               id="delivery_man_calculation_value">
                                        <small class="text-muted d-block mt-1" id="delivery_man_value_help">
                                            {{ __('Enter percentage (%) or fixed amount') }}
                                        </small>
                                    </div>
                                </div>
                            </div>

                            <hr class="my-4">

                            {{-- Points & Rewards Section --}}
                            <div class="mb-4">
                                <h6 class="fw-bold text-secondary mb-3">
                                    <i class="fas fa-star me-2 text-primary"></i>
                                    {{ __('Points & Rewards') }}
                                </h6>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">
                                            <i class="fas fa-gift text-primary me-2"></i>{{ __('Invitation Discount Points') }}
                                        </label>
                                        <input type="number" name="invitation_discount_points" min="0"
                                               class="form-control shadow-sm"
                                               value="{{ old('invitation_discount_points', $settings['invitation_discount_points'] ?? '') }}"
                                               placeholder="0">
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">
                                            <i class="fas fa-percent text-primary me-2"></i>{{ __('Order Points Rate (%)') }}
                                        </label>
                                        <input type="number" name="order_points_rate" step="0.01" min="0"
                                               class="form-control shadow-sm"
                                               value="{{ old('order_points_rate', $settings['order_points_rate'] ?? '') }}"
                                               placeholder="0.00">
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">
                                            <i class="fas fa-percent text-primary me-2"></i>{{ __('Inviter Order Points Rate (%)') }}
                                        </label>
                                        <input type="number" name="inviter_order_points_rate" step="0.01" min="0"
                                               class="form-control shadow-sm"
                                               value="{{ old('inviter_order_points_rate', $settings['inviter_order_points_rate'] ?? '') }}"
                                               placeholder="0.00">
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">
                                            <i class="fas fa-exchange-alt text-primary me-2"></i>{{ __('Point to Money Rate') }}
                                            <small class="text-muted">(1 {{ currency_symbol() }} = ? Points)</small>
                                        </label>
                                        <input type="number" name="point_to_money_rate" step="0.01" min="0"
                                               class="form-control shadow-sm"
                                               value="{{ old('point_to_money_rate', $settings['point_to_money_rate'] ?? '') }}"
                                               placeholder="0.00">
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">
                                            <i class="fas fa-tag text-primary me-2"></i>{{ __('Max Points Discount Per Order') }}
                                        </label>
                                        <input type="number" name="max_points_discount_per_order" min="0"
                                               class="form-control shadow-sm"
                                               value="{{ old('max_points_discount_per_order', $settings['max_points_discount_per_order'] ?? '') }}"
                                               placeholder="0">
                                    </div>
                                </div>
                            </div>

                            <hr class="my-4">

                            {{-- Permissions & Features Section --}}
                            <div class="mb-4">
                                <h6 class="fw-bold text-secondary mb-3">
                                    <i class="fas fa-toggle-on me-2 text-primary"></i>
                                    {{ __('Permissions & Features') }}
                                </h6>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <div class="p-3 bg-light rounded-3">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox"
                                                       name="allow_order_points"
                                                       value="1"
                                                       id="allow_order_points"
                                                       {{ old('allow_order_points', $settings['allow_order_points'] ?? '') ? 'checked' : '' }}>
                                                <label class="form-check-label fw-semibold" for="allow_order_points">
                                                    <i class="fas fa-check-circle text-success me-2"></i>{{ __('Allow Order Points') }}
                                                </label>
                                                <small class="text-muted d-block mt-1">{{ __('Enable points earning on orders') }}</small>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="p-3 bg-light rounded-3">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox"
                                                       name="allow_inviter_order_points"
                                                       value="1"
                                                       id="allow_inviter_order_points"
                                                       {{ old('allow_inviter_order_points', $settings['allow_inviter_order_points'] ?? '') ? 'checked' : '' }}>
                                                <label class="form-check-label fw-semibold" for="allow_inviter_order_points">
                                                    <i class="fas fa-user-friends text-info me-2"></i>{{ __('Allow Inviter Order Points') }}
                                                </label>
                                                <small class="text-muted d-block mt-1">{{ __('Enable points for inviters on orders') }}</small>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="p-3 bg-light rounded-3">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox"
                                                       name="allow_more_than_one_free_item"
                                                       value="1"
                                                       id="allow_more_than_one_free_item"
                                                       {{ old('allow_more_than_one_free_item', $settings['allow_more_than_one_free_item'] ?? '') ? 'checked' : '' }}>
                                                <label class="form-check-label fw-semibold" for="allow_more_than_one_free_item">
                                                    <i class="fas fa-shopping-cart text-warning me-2"></i>{{ __('Allow More Than One Free Item') }}
                                                </label>
                                                <small class="text-muted d-block mt-1">{{ __('Allow multiple free items in orders') }}</small>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="p-3 bg-light rounded-3">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox"
                                                       name="allow_branch_admin_to_edit_stock"
                                                       value="1"
                                                       id="allow_branch_admin_to_edit_stock"
                                                       {{ old('allow_branch_admin_to_edit_stock', $settings['allow_branch_admin_to_edit_stock'] ?? '') ? 'checked' : '' }}>
                                                <label class="form-check-label fw-semibold" for="allow_branch_admin_to_edit_stock">
                                                    <i class="fas fa-warehouse text-primary me-2"></i>{{ __('Allow Branch Admin to Edit Stock') }}
                                                </label>
                                                <small class="text-muted d-block mt-1">{{ __('Enable stock editing for branch administrators') }}</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-4 pt-3 border-top">
                                <button type="submit" class="btn btn-primary shadow-sm px-4">
                                    <i class="fas fa-save me-2"></i>{{ __('Save Settings') }}
                                </button>
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
        // Logo preview
        const logoInput = document.getElementById('logoInput');
        if (logoInput) {
            logoInput.addEventListener('change', function(e) {
                const previewContainer = document.getElementById('logoPreview');
                previewContainer.innerHTML = '';

                if (this.files && this.files[0]) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const label = document.createElement('label');
                        label.className = 'form-label fw-semibold';
                        label.textContent = '{{ __('New Logo Preview') }}';

                        const img = document.createElement('img');
                        img.src = e.target.result;
                        img.className = 'img-fluid rounded';
                        img.alt = 'Logo preview';

                        const div = document.createElement('div');
                        div.className = 'border rounded p-2 bg-light';
                        div.style.maxWidth = '200px';
                        div.appendChild(label);
                        div.appendChild(img);

                        previewContainer.appendChild(div);
                    };
                    reader.readAsDataURL(this.files[0]);
                }
            });
        }

        // Icon preview
        const iconInput = document.getElementById('iconInput');
        if (iconInput) {
            iconInput.addEventListener('change', function(e) {
                const previewContainer = document.getElementById('iconPreview');
                previewContainer.innerHTML = '';

                if (this.files && this.files[0]) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const label = document.createElement('label');
                        label.className = 'form-label fw-semibold';
                        label.textContent = '{{ __('New Icon Preview') }}';

                        const img = document.createElement('img');
                        img.src = e.target.result;
                        img.className = 'img-fluid rounded';
                        img.alt = 'Icon preview';

                        const div = document.createElement('div');
                        div.className = 'border rounded p-2 bg-light';
                        div.style.maxWidth = '100px';
                        div.appendChild(label);
                        div.appendChild(img);

                        previewContainer.appendChild(div);
                    };
                    reader.readAsDataURL(this.files[0]);
                }
            });
        }

        // Delivery man calculation method change handler
        const calculationMethodSelect = document.getElementById('delivery_man_calculation_method');
        const calculationValueInput = document.getElementById('delivery_man_calculation_value');
        const calculationValueHelp = document.getElementById('delivery_man_value_help');

        if (calculationMethodSelect && calculationValueInput && calculationValueHelp) {
            function updateCalculationHelp() {
                const method = calculationMethodSelect.value;
                if (method === 'percentage') {
                    calculationValueInput.placeholder = '0.00';
                    calculationValueHelp.textContent = '{{ __('Enter percentage (%) of total order') }}';
                } else if (method === 'fixed') {
                    calculationValueInput.placeholder = '0.00';
                    calculationValueHelp.textContent = '{{ __('Enter fixed amount') }} ({{ currency_symbol() }})';
                }
            }

            // Update on page load
            updateCalculationHelp();

            // Update when method changes
            calculationMethodSelect.addEventListener('change', updateCalculationHelp);
        }
    </script>
@endpush

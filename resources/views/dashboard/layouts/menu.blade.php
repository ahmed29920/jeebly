@php
    $user = auth()->user();
@endphp

<aside
    class="sidenav bg-white navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 @if (app()->getLocale() == 'en') fixed-start ms-4 @else fixed-end me-4 rotate-caret @endif"
    id="sidenav-main">
    <div class="sidenav-header">
        <i class="fas fa-times p-3 cursor-pointer text-secondary opacity-5 position-absolute end-0 top-0 d-none d-xl-none"
            aria-hidden="true" id="iconSidenav"></i>
        <a class="navbar-brand m-0" href="{{ $user->role == 'admin' ? route('admin.dashboard') : route('branch.dashboard') }}" target="_blank">
            <img src="{{ asset('storage/' . setting('app_logo')) }}" width="26px" height="26px"
                class="navbar-brand-img h-100" alt="main_logo" style="width: 100%;max-height: unset;">
        </a>
        @if ($user->role == 'employee')
        <span class="navbar-brand-text text-center w-100 ms-2 fw-bold d-none d-lg-inline-block">{{  $user->branch->name }}</span>
        @endif
    </div>
    <hr class="horizontal dark mt-0">
    <div class="collapse navbar-collapse  w-auto " id="sidenav-collapse-main">
        <ul class="navbar-nav">
            @if ($user->role == 'admin')
                @if ($user->hasRole('admin'))
                    <li class="nav-item">
                        <a class="nav-link active" href="{{ route('admin.dashboard') }}">
                            <div
                                class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                                <i class="ni ni-tv-2 text-dark text-sm opacity-10"></i>
                            </div>
                            <span class="nav-link-text ms-1">{{ __('Dashboard') }}</span>
                        </a>
                    </li>
                @endif
                @if ($user->hasRole('admin') || $user->can('Categories'))
                    <li class="nav-item">
                        <a class="nav-link " href="{{ route('admin.categories.index') }}">
                            <div
                                class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                                <i class="fa-solid fa-layer-group text-dark text-sm opacity-10"></i>
                            </div>
                            <span class="nav-link-text ms-1">{{ __('Categories') }}</span>
                        </a>
                    </li>
                @endif
                @if ($user->hasRole('admin') || $user->can('Attributes'))
                    <li class="nav-item">
                        <a class="nav-link " href="{{ route('admin.attributes.index') }}">
                            <div
                                class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                                <i class="fa-solid fa-qrcode text-dark  text-sm opacity-10"></i>
                            </div>
                            <span class="nav-link-text ms-1">{{ __('Attributes') }}</span>
                        </a>
                    </li>
                @endif
                @if ($user->hasRole('admin') || $user->can('Products'))
                    <li class="nav-item">
                        <a class="nav-link " href="{{ route('admin.products.index') }}">
                            <div
                                class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                                <i class="fa-solid fa-boxes-stacked text-dark  text-sm opacity-10"></i>
                            </div>
                            <span class="nav-link-text ms-1">{{ __('Products') }}</span>
                        </a>
                    </li>
                @endif
                @if ($user->hasRole('admin') || $user->can('Units'))
                    <li class="nav-item">
                        <a class="nav-link " href="{{ route('admin.units.index') }}">
                            <div
                                class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                                <i class="fa-solid fa-ruler text-dark text-sm opacity-10"></i>
                            </div>
                            <span class="nav-link-text ms-1">{{ __('Units') }}</span>
                        </a>
                    </li>
                @endif
                @if ($user->hasRole('admin') || $user->can('Coupons'))
                    <li class="nav-item">
                        <a class="nav-link " href="{{ route('admin.coupons.index') }}">
                            <div
                                class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                                <i class="fa-solid fa-money-bill text-dark  text-sm opacity-10"></i>
                            </div>
                            <span class="nav-link-text ms-1">{{ __('Coupons') }}</span>
                        </a>
                    </li>
                @endif
                @if ($user->hasRole('admin') || $user->can('Orders'))
                    <li class="nav-item">
                        <a class="nav-link " href="{{ route('admin.orders.index') }}">
                            <div
                                class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                                <i class="fa-solid fa-clipboard-list text-dark  text-sm opacity-10"></i>
                            </div>
                            <span class="nav-link-text ms-1">{{ __('Orders') }}</span>
                        </a>
                    </li>
                @endif
                @if ($user->hasRole('admin') || $user->can('Orders'))
                    <li class="nav-item">
                        <a class="nav-link " href="{{ route('admin.booking-lists.index') }}">
                            <div
                                class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                                <i class="fa-solid fa-calendar-check text-dark  text-sm opacity-10"></i>
                            </div>
                            <span class="nav-link-text ms-1">{{ __('Booking Lists') }}</span>
                        </a>
                    </li>
                @endif
                @if ($user->hasRole('admin') || $user->can('Transaction'))
                    <li class="nav-item">
                        <a class="nav-link " href="{{ route('admin.transactions.index') }}">
                            <div
                                class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                                <i class="fa-solid fa-money-bill-transfer text-dark  text-sm opacity-10"></i>
                            </div>
                            <span class="nav-link-text ms-1">{{ __('Transaction') }}</span>
                        </a>
                    </li>
                @endif
                @if ($user->hasRole('admin') || $user->can('Reviews'))
                    <li class="nav-item">
                        <a class="nav-link " href="{{ route('admin.reviews.index') }}">
                            <div
                                class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                                <i class="fa-solid fa-star text-dark text-sm opacity-10"></i>
                            </div>
                            <span class="nav-link-text ms-1">{{ __('Reviews') }}</span>
                        </a>
                    </li>
                @endif
                @if ($user->hasRole('admin') || $user->can('Tickets'))
                    <li class="nav-item">
                        <a class="nav-link " href="{{ route('admin.tickets.index') }}">
                            <div
                                class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                                <i class="fa-solid fa-ticket-alt text-sm text-dark  opacity-10"></i>
                            </div>
                            <span class="nav-link-text ms-1">{{ __('Tickets') }}</span>
                        </a>
                    </li>
                @endif
                @if ($user->hasRole('admin') || $user->can('Offers'))
                    <li class="nav-item">
                        <a class="nav-link " href="{{ route('admin.offers.index') }}">
                            <div
                                class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                                <i class="fa-solid fa-gift text-dark text-sm opacity-10"></i>
                            </div>
                            <span class="nav-link-text ms-1">{{ __('Offers') }}</span>
                        </a>
                    </li>
                @endif
                @if ($user->hasRole('admin') || $user->can('Branches'))
                    <li class="nav-item">
                        <a class="nav-link " href="{{ route('admin.branches.index') }}">
                            <div
                                class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                                <i class="fa-solid fa-building text-dark text-sm opacity-10"></i>
                            </div>
                            <span class="nav-link-text ms-1">{{ __('Branches') }}</span>
                        </a>
                    </li>
                @endif
                @if ($user->hasRole('admin') || $user->can('Employees'))
                    <li class="nav-item">
                        <a class="nav-link " href="{{ route('admin.employees.index') }}">
                            <div
                                class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                                <i class="fa-solid fa-user-tie text-dark text-sm opacity-10"></i>
                            </div>
                            <span class="nav-link-text ms-1">{{ __('Employees') }}</span>
                        </a>
                    </li>
                @endif
                @if ($user->hasRole('admin') || $user->can('Deliveries'))
                    <li class="nav-item">
                        <a class="nav-link " href="{{ route('admin.deliveries.index') }}">
                            <div
                                class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                                <i class="fa-solid fa-truck text-dark text-sm opacity-10"></i>
                            </div>
                            <span class="nav-link-text ms-1">{{ __('Deliveries') }}</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link " href="{{ route('admin.delivery-wallet-requests.index') }}">
                            <div
                                class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                                <i class="fa-solid fa-wallet text-dark text-sm opacity-10"></i>
                            </div>
                            <span class="nav-link-text ms-1">{{ __('Wallet Requests') }}</span>
                        </a>
                    </li>
                @endif
                @if ($user->hasRole('admin') || $user->can('Zones'))
                    <li class="nav-item">
                        <a class="nav-link " href="{{ route('admin.zones.index') }}">
                            <div
                                class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                                <i class="fa-solid fa-draw-polygon text-dark text-sm opacity-10"></i>
                            </div>
                            <span class="nav-link-text ms-1">{{ __('Zones') }}</span>
                        </a>
                    </li>
                @endif
                @if ($user->hasRole('admin') || $user->can('Variants'))
                    <li class="nav-item">
                        <a class="nav-link " href="{{ route('admin.variants.index') }}">
                            <div
                                class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                                <i class="fa-solid fa-list-ul text-dark text-sm opacity-10"></i>
                            </div>
                            <span class="nav-link-text ms-1">{{ __('Variants') }}</span>
                        </a>
                    </li>
                @endif
                @if ($user->hasRole('admin') || $user->can('Sliders'))
                    <li class="nav-item">
                        <a class="nav-link " href="{{ route('admin.sliders.index') }}">
                            <div
                                class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                                <i class="fa-solid fa-images text-dark text-sm opacity-10"></i>
                            </div>
                            <span class="nav-link-text ms-1">{{ __('Sliders') }}</span>
                        </a>
                    </li>
                @endif
                @if ($user->hasRole('admin'))
                    <li class="nav-item">
                        <a class="nav-link " href="{{ route('admin.statistics') }}">
                            <div
                                class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                                <i class="fa-solid fa-chart-line text-sm text-dark  opacity-10"></i>
                            </div>
                            <span class="nav-link-text ms-1">{{ __('Statistics') }}</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link " href="{{ route('admin.roles.index') }}">
                            <div
                                class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                                <i class="fa-solid fa-key text-sm text-dark  opacity-10"></i>
                            </div>
                            <span class="nav-link-text ms-1">{{ __('Roles') }}</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link " href="{{ route('admin.users.index', ['role' => 'user']) }}">
                            <div
                                class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                                <i class="fa-solid fa-users text-sm text-dark  opacity-10"></i>
                            </div>
                            <span class="nav-link-text ms-1">{{ __('Users') }}</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link " href="{{ route('admin.users.index', ['role' => 'admin']) }}">
                            <div
                                class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                                <i class="fa-solid fa-users text-sm text-dark  opacity-10"></i>
                            </div>
                            <span class="nav-link-text ms-1">{{ __('Admins') }}</span>
                        </a>
                    </li>
                @endif
		<li class="nav-item">
                        <a class="nav-link " href="{{ route('admin.notifications.create') }}">
                            <div
                                class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                                <i class="fa-solid fa-bell text-sm text-dark opacity-10"></i>
                            </div>
                            <span class="nav-link-text ms-1">{{ __('Notifications') }}</span>
	        	</a>
            	</li>
                @if ($user->hasRole('admin') || $user->can('Settings'))
                    <li class="nav-item">
                        <a class="nav-link " href="{{ route('admin.settings.index') }}">
                            <div
                                class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                                <i class="fa-solid fa-gear text-dark text-sm opacity-10"></i>
                            </div>
                            <span class="nav-link-text ms-1">{{ __('Settings') }}</span>
                        </a>
                    </li>
                @endif
            @elseif ($user->role == 'employee')
                @if ($user->hasRole('employee'))
                    <li class="nav-item">
                        <a class="nav-link active" href="{{ route('branch.dashboard') }}">
                            <div
                                class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                                <i class="ni ni-tv-2 text-dark text-sm opacity-10"></i>
                            </div>
                            <span class="nav-link-text ms-1">{{ __('Dashboard') }}</span>
                        </a>
                    </li>
                @endif
                @if ($user->hasRole('employee') || $user->can('Categories'))
                    <li class="nav-item">
                        <a class="nav-link " href="{{ route('branch.categories.index') }}">
                            <div
                                class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                                <i class="fa-solid fa-layer-group text-dark text-sm opacity-10"></i>
                            </div>
                            <span class="nav-link-text ms-1">{{ __('Categories') }}</span>
                        </a>
                    </li>
                @endif
                @if ($user->hasRole('employee') || $user->can('Products'))
                    <li class="nav-item">
                        <a class="nav-link " href="{{ route('branch.products.index') }}">
                            <div
                                class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                                <i class="fa-solid fa-boxes-stacked text-dark  text-sm opacity-10"></i>
                            </div>
                            <span class="nav-link-text ms-1">{{ __('Products') }}</span>
                        </a>
                    </li>
                @endif
                @if ($user->hasRole('employee') || $user->can('Orders'))
                    <li class="nav-item">
                        <a class="nav-link " href="{{ route('branch.orders.index') }}">
                            <div
                                class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                                <i class="fa-solid fa-calendar-check text-dark  text-sm opacity-10"></i>
                            </div>
                            <span class="nav-link-text ms-1">{{ __('Orders') }}</span>
                        </a>
                    </li>
                @endif
                @if ($user->hasRole('employee') || $user->can('Transaction'))
                    <li class="nav-item">
                        <a class="nav-link " href="{{ route('branch.transactions.index') }}">
                            <div
                                class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                                <i class="fa-solid fa-money-bill-transfer text-dark  text-sm opacity-10"></i>
                            </div>
                            <span class="nav-link-text ms-1">{{ __('Transaction') }}</span>
                        </a>
                    </li>
                @endif
                @if ($user->hasRole('employee'))
                    <li class="nav-item">
                        <a class="nav-link " href="{{ route('branch.employees.index') }}">
                            <div
                                class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                                <i class="fa-solid fa-user-plus text-dark  text-sm opacity-10"></i>
                            </div>
                            <span class="nav-link-text ms-1">{{ __('Employees') }}</span>
                        </a>
                    </li>
                @endif
                @if ($user->hasRole('employee') || $user->can('Statistics'))
                    <li class="nav-item">
                        <a class="nav-link " href="{{ route('branch.statistics') }}">
                            <div
                                class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                                <i class="fa-solid fa-chart-line text-sm text-dark  opacity-10"></i>
                            </div>
                            <span class="nav-link-text ms-1">{{ __('Statistics') }}</span>
                        </a>
                    </li>
                @endif
                @if ($user->hasRole('employee') || $user->can('Deliveries'))
                    <li class="nav-item">
                        <a class="nav-link " href="{{ route('branch.deliveries.index') }}">
                            <div
                                class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                                <i class="fa-solid fa-truck text-dark text-sm opacity-10"></i>
                            </div>
                            <span class="nav-link-text ms-1">{{ __('Deliveries') }}</span>
                        </a>
                    </li>
                @endif
                {{-- Stock History --}}
                @if ($user->hasRole('employee') || $user->can('Stock History'))
                    <li class="nav-item">
                        <a class="nav-link " href="{{ route('branch.stock-history.index') }}">
                            <div
                                class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                                <i class="fa-solid fa-box text-dark text-sm opacity-10"></i>
                            </div>
                            <span class="nav-link-text ms-1">{{ __('Stock History') }}</span>
                        </a>
                    </li>

                @endif
            @endif
        </ul>
    </div>

</aside>

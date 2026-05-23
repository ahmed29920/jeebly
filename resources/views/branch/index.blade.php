    @extends('dashboard.layouts.app')
@section('content')
    <div class="container-fluid py-4">
        <h5 class="text-white">Today’s Details</h5>
        <div class="row">
            {{-- Today’s Money --}}
            <div class="col-xl-4 col-sm-6 mb-xl-0 mb-4">
                <div class="card">
                    <div class="card-body p-3">
                        <div class="row">
                            <div class="col-8">
                                <div class="numbers">
                                    <p class="text-sm mb-0 text-uppercase font-weight-bold">{{ __("Today's Money") }}</p>
                                    <h5 class="font-weight-bolder">
                                        {{ format_currency($todaysSales) }}
                                    </h5>
                                </div>
                            </div>
                            <div class="col-4 text-end">
                                <div class="icon icon-shape bg-gradient-primary shadow-primary text-center rounded-circle">
                                    <i class="ni ni-money-coins text-lg opacity-10" aria-hidden="true"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            {{-- Today’s Orders --}}
            <div class="col-xl-4 col-sm-6 mb-xl-0 mb-4">
                <div class="card">
                    <div class="card-body p-3">
                        <div class="row">
                            <div class="col-8">
                                <div class="numbers">
                                    <p class="text-sm mb-0 text-uppercase font-weight-bold">{{ __("Today's Orders") }}</p>
                                    <h5 class="font-weight-bolder">
                                        {{ $todaysOrders }}
                                    </h5>
                                </div>
                            </div>
                            <div class="col-4 text-end">
                                <div class="icon icon-shape bg-gradient-success shadow-success text-center rounded-circle">
                                    <i class="ni ni-paper-diploma text-lg opacity-10" aria-hidden="true"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Pending Orders --}}
            <div class="col-xl-4 col-sm-6">
                <div class="card">
                    <div class="card-body p-3">
                        <div class="row">
                            <div class="col-8">
                                <div class="numbers">
                                    <p class="text-sm mb-0 text-uppercase font-weight-bold">{{ __('Pending Orders') }}</p>
                                    <h5 class="font-weight-bolder">
                                        {{ $pendingOrders }}
                                    </h5>
                                </div>
                            </div>
                            <div class="col-4 text-end">
                                <div class="icon icon-shape bg-gradient-warning shadow-warning text-center rounded-circle">
                                    <i class="ni ni-cart text-lg opacity-10" aria-hidden="true"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <h5 class="text-white mt-3">Overall Details</h5>
        {{-- Extra Stats --}}
        <div class="row">
            {{-- Total Sales --}}
            <div class="col-xl-4 col-sm-6 mb-xl-0 mb-4">
                <div class="card">
                    <div class="card-body p-3">
                        <div class="row">
                            <div class="col-8">
                                <div class="numbers">
                                    <p class="text-sm mb-0 text-uppercase font-weight-bold">{{ __('Total Sales') }}</p>
                                    <h5 class="font-weight-bolder">
                                        {{ format_currency($totalSales) }}
                                    </h5>
                                </div>
                            </div>
                            <div class="col-4 text-end">
                                <div class="icon icon-shape bg-gradient-info shadow-info text-center rounded-circle">
                                    <i class="ni ni-credit-card text-lg opacity-10" aria-hidden="true"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Average Order Sale --}}
            <div class="col-xl-4 col-sm-6 mb-xl-0 mb-4">
                <div class="card">
                    <div class="card-body p-3">
                        <div class="row">
                            <div class="col-8">
                                <div class="numbers">
                                    <p class="text-sm mb-0 text-uppercase font-weight-bold">{{ __('Average Order Sale') }}
                                    </p>
                                    <h5 class="font-weight-bolder">
                                        {{ format_currency($averageOrderSale) }}
                                    </h5>
                                </div>
                            </div>
                            <div class="col-4 text-end">
                                <div
                                    class="icon icon-shape bg-gradient-secondary shadow-secondary text-center rounded-circle">
                                    <i class="ni ni-chart-bar-32 text-lg opacity-10" aria-hidden="true"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Unpaid Invoices --}}
            <div class="col-xl-4 col-sm-6">
                <div class="card">
                    <div class="card-body p-3">
                        <div class="row">
                            <div class="col-8">
                                <div class="numbers">
                                    <p class="text-sm mb-0 text-uppercase font-weight-bold">{{ __('Unpaid Invoices') }}</p>
                                    <h5 class="font-weight-bolder">
                                        {{ format_currency($unpaidInvoices) }}
                                    </h5>
                                </div>
                            </div>
                            <div class="col-4 text-end">
                                <div class="icon icon-shape bg-gradient-danger shadow-danger text-center rounded-circle">
                                    <i class="ni ni-bullet-list-67 text-lg opacity-10" aria-hidden="true"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="row mt-4">
            <div class="col-lg-7 mb-lg-0 mb-4">
                <div class="card ">
                    <div class="card-header pb-0 p-3">
                        <div class="d-flex justify-content-between">
                            <h6 class="mb-2">{{ __('Low Stock Products') }}</h6>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table align-items-center ">
                            <tbody>
                                @foreach ($lowStockProducts as $product)
                                    <tr>
                                        <td class="w-30">
                                            <div class="d-flex px-2 py-1 align-items-center">
                                                <div>
                                                    <img src="{{ $product->images()->first()->image_path }}"
                                                        class="avatar avatar-xl" alt="Country flag">
                                                </div>
                                                <div class="ms-4">
                                                    <p class="text-xs font-weight-bold mb-0">
                                                        {{ $product->getTranslation('name', 'en') }}</p>
                                                    <h6 class="text-sm mb-0">{{ $product->sku }}</h6>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="text-center">
                                                <p class="text-xs font-weight-bold mb-0 d-inline">{{ __('Price') }}:</p>
                                                <h6 class="text-sm mb-0 d-inline mx-2">{{ format_currency($product->manager()->price()) }}</h6>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="text-center">
                                                <p class="text-xs font-weight-bold mb-0 h-5 d-inline">
                                                    {{ __('Stock') }}:
                                                </p>
                                                <h6 class="text-sm mb-0 d-inline mx-2">
                                                    {{ $product->manager()->stock() }}
                                                </h6>
                                            </div>
                                        </td>
                                        <td>
                                            {{-- toggle bookable --}}
                                            <div class="text-center">
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input bookable-switch" type="checkbox"
                                                        id="flexSwitchCheckDefault" data-product-id="{{ $product->id }}"
                                                        {{ $product->is_bookable ? 'checked' : '' }}>
                                                    <label class="form-check-label"
                                                        for="flexSwitchCheckDefault">{{ __('Bookable') }}</label>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="align-middle text-sm">
                                            <div class="col text-center">
                                                <h6 class="text-sm mb-0">
                                                    @if ($product->is_active)
                                                        <span class="badge bg-success">{{ __('Active') }}</span>
                                                    @else
                                                        <span class="badge bg-danger">{{ __('Not Active') }}</span>
                                                    @endif
                                                </h6>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-lg-5">
                <div class="card">
                    <div class="card-header pb-0 p-3">
                        <h6 class="mb-0">{{ __('Top Customers') }}</h6>
                    </div>
                    <div class="card-body p-3">
                        <ul class="list-group">
                            @foreach ($topCustomers as $user)
                                <li
                                    class="list-group-item border-0 d-flex justify-content-between ps-0 mb-2 border-radius-lg">
                                    <div class="d-flex align-items-center">
                                        @if ($user->image)
                                            <div
                                                class="icon icon-shape rounded rounded-circle align-content-center me-3  text-center">
                                                <img src="{{ asset('storage/' . $user->image) }}" class="w-100"
                                                    alt="">
                                            </div>
                                        @else
                                            <div
                                                class="icon icon-shape rounded rounded-circle align-content-center me-3 bg-gradient-dark shadow text-center">
                                                <span class="text-white">A</span>
                                            </div>
                                        @endif
                                        <div class="d-flex flex-column">
                                            <h6 class="mb-1 text-dark text-sm">{{ $user->name }} {{ $user->id }}
                                            </h6>
                                            <span class="font-weight-bold">{{ $user->orders_count }}
                                                {{ __('orders') }}</span>
                                        </div>
                                    </div>
                                    <div class="d-flex">
                                        <button
                                            class="btn btn-link btn-icon-only btn-rounded btn-sm text-dark icon-move-right my-auto">
                                            <i class="ni ni-bold-right" aria-hidden="true"></i>
                                        </button>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {

            $('.bookable-switch').on('change', function() {
                let productId = $(this).data('product-id');
                $.ajax({
                    url: '{{ route('admin.products.updateBookable', ':id') }}'.replace(':id',
                        productId),
                    type: 'get',
                    data: {
                        _token: '{{ csrf_token() }}',
                        is_bookable: $(this).prop('checked')
                    },
                    success: function() {
                        toastr.success('Bookable status updated');
                    },
                    error: function() {
                        toastr.error('Something went wrong');
                    }
                });
            });

        });
    </script>
@endpush

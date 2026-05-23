@extends('dashboard.layouts.app')

@section('content')
<div class="container-fluid py-4">

    {{-- Top Selling Products --}}
    <div class="row">
        <div class="col-lg-6">
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <h6>{{ __('Top Selling Products') }}</h6>
                </div>
                <div class="card-body px-0 pt-0 pb-2">
                    <div class="table-responsive p-0">
                        <table class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th>{{ __('Product') }}</th>
                                    <th>{{ __('Total Sold') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($topSellingProducts as $product)
                                    <tr>
                                        <td>{{ $product->getTranslation('name', 'en') }}</td>
                                        <td>{{ $product->total_sold }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- Customers With Most Orders --}}
        <div class="col-lg-6">
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <h6>{{ __('Customers With Most Orders') }}</h6>
                </div>
                <div class="card-body px-0 pt-0 pb-2">
                    <div class="table-responsive p-0">
                        <table class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th>{{ __('Customer') }}</th>
                                    <th>{{ __('Orders Count') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($topCustomers as $customer)
                                    <tr>
                                        <td>{{ $customer->name }}</td>
                                        <td>{{ $customer->orders_count }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Orders Over Time + Customers Over Time --}}
    <div class="row mt-4">
        <div class="col-lg-6">
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <h6>{{ __('Orders Over Time') }}</h6>
                </div>
                <div class="card-body">
                    <canvas id="ordersChart"></canvas>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <h6>{{ __('Customers Over Time') }}</h6>
                </div>
                <div class="card-body">
                    <canvas id="customersChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    {{-- Products Quantities Sold Over Time --}}
    <div class="row mt-4">
        <div class="col-lg-12">
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <h6>{{ __('Products Quantities Sold Over Time') }}</h6>
                </div>
                <div class="card-body">
                    <canvas id="productsSoldChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    {{-- Wishlist + Reviews --}}
    <div class="row mt-4">
        <div class="col-lg-6">
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <h6>{{ __('Most Products Added To Wishlist') }}</h6>
                </div>
                <div class="card-body px-0 pt-0 pb-2">
                    <div class="table-responsive p-0">
                        <table class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th>{{ __('Product') }}</th>
                                    <th>{{ __('Wishlist Count') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($wishlistProducts as $product)
                                    <tr>
                                        <td>{{ $product->name  }}</td>
                                        <td>{{ $product->total_wishlist }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <h6>{{ __('Products With Most Reviews') }}</h6>
                </div>
                <div class="card-body px-0 pt-0 pb-2">
                    <div class="table-responsive p-0">
                        <table class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th>{{ __('Product') }}</th>
                                    <th>{{ __('Reviews Count') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($topReviewedProducts as $product)
                                    <tr>
                                        <td>{{ $product->getTranslation('name', 'en')  }}</td>
                                        <td>{{ $product->reviews_count }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Orders Over Time
    const ordersData = @json($ordersOverTime);
    console.log(ordersData)
    new Chart(document.getElementById('ordersChart'), {
        type: 'line',
        data: {
            labels: ordersData.map(item => item.month),
            datasets: [{
                label: 'Orders',
                data: ordersData.map(item => item.total),
                borderColor: '#4e73df',
                backgroundColor: 'rgba(78, 115, 223, 0.1)',
                tension: 0.3
            }]
        }
    });

    // Customers Over Time
    const customersData = @json($customersOverTime);
    new Chart(document.getElementById('customersChart'), {
        type: 'line',
        data: {
            labels: customersData.map(item => item.month),
            datasets: [{
                label: 'Customers',
                data: customersData.map(item => item.total),
                borderColor: '#1cc88a',
                backgroundColor: 'rgba(28, 200, 138, 0.1)',
                tension: 0.3
            }]
        }
    });

    // Products Quantities Sold Over Time
    const productsData = @json($productsSoldOverTime);

        // Extract months & group data
        const months = [...new Set(productsData.map(item => item.month))];
        const groupedProducts = {};
        productsData.forEach(item => {
            if (!groupedProducts[item.name]) {
                groupedProducts[item.name] = {};
            }
            groupedProducts[item.name][item.month] = item.total_sold;
        });

        const datasets = Object.keys(groupedProducts).map((product, i) => ({
            label: product,
            data: months.map(month => groupedProducts[product][month] || 0),
            borderColor: `hsl(${i * 50}, 70%, 50%)`,
            fill: false,
            tension: 0.3
        }));

        new Chart(document.getElementById('productsSoldChart'), {
            type: 'line',
            data: {
                labels: months,
                datasets: datasets
            }
        });
</script>
@endpush

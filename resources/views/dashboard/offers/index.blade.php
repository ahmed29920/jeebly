@extends('dashboard.layouts.app')

@section('content')
    <div class="container-fluid py-4">
        <div class="row mb-3">
            <div class="col-12 text-end">
                <a href="{{ route('admin.offers.create') }}" class="btn btn-primary">
                    <i class="fa fa-plus"></i> {{ __('Add Offer') }}
                </a>
            </div>
        </div>

        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6>{{ __('Offers') }}</h6>
            </div>

            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div id="offers-table_length"></div>
                    <div id="offers-table_filter"></div>
                </div>
                <table id="offers-table" class="table">
                    <thead>
                        <tr>
                            <th class="text-center">ID</th>
                            <th class="text-center">{{ __('Title') }}</th>
                            <th class="text-center">{{ __('Image') }}</th>
                            <th class="text-center">{{ __('Type') }}</th>
                            <th class="text-center">{{ __('Discount Type') }}</th>
                            <th class="text-center">{{ __('Discount Value') }}</th>
                            <th class="text-center">{{ __('Start Date') }}</th>
                            <th class="text-center">{{ __('End Date') }}</th>
                            <th class="text-center">{{ __('Status') }}</th>
                            <th class="text-center">{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($offers as $offer)
                            <tr>
                                <td class="align-content-center text-center">{{ $offer->id }}</td>
                                <td class="align-content-center text-center">{{ $offer->title }}</td>
                                <td class="align-content-center text-center">
                                    <img src="{{ asset('storage/' . $offer->image) }}" alt="{{ $offer->title }}" class="img-fluid" style="max-height: 100px;">
                                </td>
                                <td class="align-content-center text-center">
                                    <span class="badge bg-info">{{ ucfirst($offer->type) }}</span>
                                </td>
                                <td class="align-content-center text-center">
                                    <span class="badge bg-secondary">{{ ucfirst(str_replace('_', ' ', $offer->discount_type)) }}</span>
                                </td>
                                <td class="align-content-center text-center">
                                    @if($offer->discount_type === 'percent')
                                        {{ number_format($offer->discount_value, 2) }}%
                                    @elseif($offer->discount_type === 'free_shipping')
                                        {{ __('Free Shipping') }}
                                    @else
                                        {{ format_currency($offer->discount_value) }}
                                    @endif
                                </td>
                                <td class="align-content-center text-center">
                                    {{ $offer->start_date ? $offer->start_date->format('Y-m-d H:i') : __('N/A') }}
                                </td>
                                <td class="align-content-center text-center">
                                    {{ $offer->end_date ? $offer->end_date->format('Y-m-d H:i') : __('N/A') }}
                                </td>
                                <td class="align-content-center text-center">
                                    @if($offer->is_active)
                                        <span class="badge bg-success">{{ __('Active') }}</span>
                                    @else
                                        <span class="badge bg-danger">{{ __('Inactive') }}</span>
                                    @endif
                                </td>
                                <td class="align-content-center text-center">
                                    <a href="{{ route('admin.offers.edit', $offer->id) }}"
                                        class="text-warning mx-2 btn-sm" title="{{ __('Edit') }}">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.offers.toggle-status', $offer->id) }}" method="POST"
                                        class="d-inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="text-info bg-white border-0 btn-sm"
                                            title="{{ $offer->is_active ? __('Deactivate') : __('Activate') }}">
                                            <i class="fa fa-{{ $offer->is_active ? 'toggle-on' : 'toggle-off' }}"></i>
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.offers.destroy', $offer->id) }}" method="POST"
                                        class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="delete-btn text-danger bg-white border-0 btn-sm"
                                            title="{{ __('Delete') }}">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <link rel="stylesheet" href="//cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <script src="//cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function() {
            let table = $('#offers-table').DataTable({
                dom: '<"top-controls row mb-3"<"col-md-6"l><"col-md-6"f>>rtip',
                pageLength: 15,
                order: [[0, 'desc']]
            });
        });

        handleDeleteAjax('.delete-btn', '{{ __('Offer has been deleted successfully.') }}');
    </script>
@endpush


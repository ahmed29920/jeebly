@extends('dashboard.layouts.app')

@section('content')
    <div class="container-fluid py-4">
        <div class="row mb-3">
            <div class="col-12 text-end">
                <a href="{{ route('branch.deliveries.create') }}" class="btn btn-primary">
                    <i class="fa fa-plus"></i> {{ __('Add Delivery') }}
                </a>
            </div>
        </div>

        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6>{{ __('Deliveries') }}</h6>
            </div>

            <div class="card-body">
                <table id="deliveries-table" class="table">
                    <thead>
                        <tr>
                            <th class="text-center">{{ __('ID') }}</th>
                            <th class="text-center">{{ __('Name') }}</th>
                            <th class="text-center">{{ __('Phone') }}</th>
                            <th class="text-center">{{ __('Plate Number') }}</th>
                            <th class="text-center">{{ __('Vehicle Name') }}</th>
                            <th class="text-center">{{ __('Vehicle Type') }}</th>
                            <th class="text-center">{{ __('Vehicle Color') }}</th>
                            <th class="text-center">{{ __('Wallet') }}</th>
                            <th class="text-center">{{ __('Status') }}</th>
                            <th class="text-center">{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($deliveries as $delivery)
                            <tr>
                                <td class="align-content-center text-center">{{ $delivery->id }}</td>
                                <td class="align-content-center text-center">
                                    {{ $delivery->user->name }}
                                </td>
                                <td class="align-content-center text-center">
                                    {{ $delivery->user->phone }}
                                </td>
                                <td class="align-content-center text-center">
                                    {{ $delivery->plate_number }}
                                </td>
                                <td class="align-content-center text-center">
                                    {{ $delivery->vehicle_name }}
                                </td>
                                <td class="align-content-center text-center">
                                    {{ $delivery->vehicle_type }}
                                </td>
                                <td class="align-content-center text-center">
                                    {{ $delivery->vehicle_color }}
                                </td>
                                <td class="align-content-center text-center">
                                    {{ format_currency($delivery->wallet) }}
                                </td>
                                <td class="align-content-center text-center">
                                    @if($delivery->is_online)
                                        <span class="badge bg-success">{{ __('Online') }}</span>
                                    @else
                                        <span class="badge bg-secondary">{{ __('Offline') }}</span>
                                    @endif
                                </td>
                                <td class="align-content-center text-center">
                                    <a href="{{ route('branch.deliveries.edit', $delivery->id) }}" class="mx-2 text-warning">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                    <a href="{{ route('branch.deliveries.show', $delivery->id) }}" class="mx-2 text-info">
                                        <i class="fa fa-eye"></i>
                                    </a>
                                    <form action="{{ route('branch.deliveries.destroy', $delivery->id) }}" method="POST"
                                        class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="delete-btn text-danger bg-white border-0 btn-sm">
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
            $('#deliveries-table').DataTable({
                dom: '<"top-controls row mb-3"<"col-md-6"l><"col-md-6"f>>rtip',
            });
        });

        handleDeleteAjax('.delete-btn', 'Delivery has been deleted successfully.');
    </script>
@endpush


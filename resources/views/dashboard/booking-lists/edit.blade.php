@extends('dashboard.layouts.app')

@section('content')
    <div class="container-fluid py-4">
        <div class="row mb-3">
            <div class="col-12">
                <a href="{{ route('admin.booking-lists.index') }}" class="btn btn-secondary">
                    <i class="fa fa-arrow-left"></i> Back to List
                </a>
                <a href="{{ route('admin.booking-lists.show', $bookingList->id) }}" class="btn btn-info">
                    <i class="fa fa-eye"></i> View Details
                </a>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h6>Edit Booking List #{{ $bookingList->id }}</h6>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.booking-lists.update', $bookingList->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="expected_at">Expected At <span class="text-danger">*</span></label>
                            <input type="datetime-local"
                                   name="expected_at"
                                   id="expected_at"
                                   class="form-control @error('expected_at') is-invalid @enderror"
                                   value="{{ old('expected_at', $bookingList->expected_at ? $bookingList->expected_at->format('Y-m-d\TH:i') : '') }}"
                                   required>
                            @error('expected_at')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Select the expected date and time for this booking</small>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="status">Status <span class="text-danger">*</span></label>
                            <select name="status"
                                    id="status"
                                    class="form-control @error('status') is-invalid @enderror"
                                    required>
                                <option value="pending" {{ old('status', $bookingList->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="confirmed" {{ old('status', $bookingList->status) == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                                <option value="fulfilled" {{ old('status', $bookingList->status) == 'fulfilled' ? 'selected' : '' }}>Fulfilled</option>
                                <option value="cancelled" {{ old('status', $bookingList->status) == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Update the booking status</small>
                        </div>
                    </div>

                    {{-- Display Current Information --}}
                    <div class="row mt-4">
                        <div class="col-md-12">
                            <div class="alert shadow-sm">
                                <h6>Current Booking Information:</h6>
                                <p class="mb-1"><strong>User:</strong> {{ $bookingList->user->name ?? 'N/A' }}</p>
                                <p class="mb-1"><strong>Product:</strong> {{ $bookingList->product->name ?? 'Product Deleted' }}</p>
                                <p class="mb-1"><strong>Quantity:</strong> {{ $bookingList->quantity }}</p>
                                <p class="mb-0"><strong>Current Status:</strong>
                                    <span class="badge bg-{{ $bookingList->status == 'confirmed' ? 'success' : ($bookingList->status == 'pending' ? 'warning' : ($bookingList->status == 'cancelled' ? 'danger' : 'info')) }}">
                                        {{ ucfirst($bookingList->status ?? 'pending') }}
                                    </span>
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-12">
                            <button type="submit" class="btn btn-primary">
                                <i class="fa fa-save"></i> Update Booking
                            </button>
                            <a href="{{ route('admin.booking-lists.index') }}" class="btn btn-secondary">
                                Cancel
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            // Set minimum date to today
            const today = new Date().toISOString().slice(0, 16);
            $('#expected_at').attr('min', today);
        });
    </script>
@endpush


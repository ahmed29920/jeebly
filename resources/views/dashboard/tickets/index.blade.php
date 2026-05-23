@extends('dashboard.layouts.app')

@section('title', 'Support Tickets')

@section('content')
<div class="container-fluid py-4">

    {{-- Header --}}
    <div class="card border-0 shadow-sm mb-4 rounded-4">
        <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center py-3">
            <div>
                <h4 class="mb-0 text-purple fw-bold">
                    <i class="fas fa-ticket-alt me-2 text-purple"></i> {{ __('Support Tickets') }}
                </h4>
                <small class="text-muted">{{ __('Manage and respond to support tickets') }}</small>
            </div>
        </div>

        {{-- Filters --}}
        <div class="border-top py-3 px-4 ">
            <form id="filterForm" method="GET" class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label class="form-label fw-semibold text-secondary">
                        <i class="fas fa-search me-1"></i> {{ __('Search') }}
                    </label>
                    <input type="text" name="search" value="{{ request('search') }}" class="form-control shadow-sm"
                        placeholder="{{ __('Search by subject...') }}">
                </div>

                <div class="col-md-3">
                    <label class="form-label fw-semibold text-secondary">
                        <i class="fas fa-filter me-1"></i> {{ __('Status') }}
                    </label>
                    <select name="status" class="form-select shadow-sm">
                        <option value="">{{ __('All Statuses') }}</option>
                        <option value="open" {{ request('status') == 'open' ? 'selected' : '' }}>{{ __('Open') }}</option>
                        <option value="hold" {{ request('status') == 'hold' ? 'selected' : '' }}>{{ __('On Hold') }}</option>
                        <option value="solved" {{ request('status') == 'solved' ? 'selected' : '' }}>{{ __('Solved') }}</option>
                        <option value="closed" {{ request('status') == 'closed' ? 'selected' : '' }}>{{ __('Closed') }}</option>
                    </select>
                </div>

                <div class="col-md-3 d-flex gap-2">
                    <button class="btn btn-purple mb-0 flex-grow-1 shadow-sm">
                        <i class="fas fa-search"></i> {{ __('Filter') }}
                    </button>
                    <a href="{{ route('admin.tickets.index') }}" class="btn btn-outline-secondary mb-0   flex-grow-1 shadow-sm">
                        <i class="fas fa-times"></i> {{ __('Clear') }}
                    </a>
                </div>
            </form>
        </div>

        {{-- Tickets Table --}}
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table align-items-center mb-0 table-hover">
                    <thead class="">
                        <tr>
                            <th class="text-secondary fw-semibold ps-4">#</th>
                            <th class="text-secondary fw-semibold">{{ __('Subject') }}</th>
                            <th class="text-secondary fw-semibold">{{ __('From') }}</th>
                            <th class="text-secondary fw-semibold">{{ __('Status') }}</th>
                            <th class="text-secondary fw-semibold">{{ __('Last Update') }}</th>
                            <th class="text-secondary fw-semibold pe-4">{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody id="ticketTableBody">
                        @include('dashboard.tickets._rows', ['tickets' => $tickets])
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Pagination --}}
        <div class="card-footer bg-white border-0 py-3">
            <div class="d-flex justify-content-center">
                {{ $tickets->links() }}
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $('#filterForm').on('submit', function(e) {
        e.preventDefault();

        $.ajax({
            url: "{{ route('admin.tickets.filter') }}",
            method: "GET",
            data: $(this).serialize(),
            beforeSend: function() {
                $('#ticketTableBody').html(
                    '<tr><td colspan="6" class="text-center py-4 text-muted"><i class="fas fa-spinner fa-spin me-2"></i> {{ __("Loading...") }}</td></tr>'
                );
            },
            success: function(response) {
                $('#ticketTableBody').html(response.html);
            },
            error: function() {
                $('#ticketTableBody').html(
                    '<tr><td colspan="6" class="text-center py-4 text-danger"><i class="fas fa-exclamation-triangle me-1"></i> {{ __("Failed to load data") }}</td></tr>'
                );
            }
        });
    });
</script>
@endsection

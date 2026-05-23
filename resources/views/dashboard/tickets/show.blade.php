@extends('dashboard.layouts.app')

@section('title', 'Ticket Details')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-lg-11 mx-auto">

            {{-- Ticket Info --}}
            <div class="card border-0 shadow-sm mb-4 rounded-4">
                <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center py-3">
                    <div>
                        <h5 class="mb-0 text-purple fw-bold">
                            <i class="fas fa-ticket-alt me-2 text-purple"></i>
                            {{ __('Ticket') }} #{{ $ticket->id }} — {{ $ticket->subject }}
                        </h5>
                        <small class="text-muted">{{ __('Detailed information about the ticket') }}</small>
                    </div>
                    <span class="badge bg-gradient-info px-3 py-2 text-uppercase shadow-sm">
                        {{ $ticket->ticket_from }}
                    </span>
                </div>

                <div class="card-body">
                    <div class="mb-3">
                        <h6 class="fw-semibold text-secondary">{{ __('Description') }}</h6>
                        <p class="text-muted mb-0">{{ $ticket->description }}</p>
                    </div>

                    @if($ticket->attachments_url && count($ticket->attachments_url))
                        <div class="mb-3">
                            <h6 class="fw-semibold text-secondary">{{ __('Attachments') }}</h6>
                            <ul class="list-unstyled">
                                @foreach($ticket->attachments_url as $attachment)
                                    <li>
                                        <a href="{{ $attachment }}" target="_blank" class="text-purple text-decoration-none">
                                            <i class="fas fa-paperclip me-1"></i>
                                            Download Attachment ({{ pathinfo($attachment, PATHINFO_EXTENSION) }})
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="mb-3">
                        <h6 class="fw-semibold text-secondary">{{ __('Status') }}</h6>
                        <span class="badge px-3 py-2 rounded-pill
                            @if($ticket->status == 'open') bg-success
                            @elseif($ticket->status == 'hold') bg-warning text-dark
                            @elseif($ticket->status == 'solved') bg-primary
                            @else bg-secondary @endif">
                            {{ ucfirst($ticket->status) }}
                        </span>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <p class="mb-1"><strong>{{ __('Created by:') }}</strong> {{ $ticket->user->name ?? 'N/A' }}</p>
                        </div>
                        <div class="col-md-6 text-md-end">
                            <p class="mb-1"><strong>{{ __('Created at:') }}</strong> {{ $ticket->created_at->format('d M Y, h:i A') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Messages --}}
            <div class="card border-0 shadow-sm mb-4 rounded-4">
                <div class="card-header bg-light border-0 d-flex justify-content-between align-items-center py-3">
                    <h6 class="mb-0 fw-bold text-purple"><i class="fas fa-comments me-2"></i> {{ __('Conversation') }}</h6>
                    <button class="btn btn-sm btn-primary shadow-sm" id="replyBtn">
                        <i class="fas fa-reply me-1"></i> {{ __('Reply') }}
                    </button>
                </div>
                <div class="card-body bg-white" style="max-height: 500px; overflow-y: auto;">
                    @forelse($ticket->messages as $message)
                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <strong class="text-purple">{{ $message->sender->name }}</strong>
                                <small class="text-muted">{{ $message->created_at->diffForHumans() }}</small>
                            </div>
                            <div class="mt-1 mb-2">
                                <small class="badge bg-secondary text-uppercase">{{ ucfirst($message->sender_type) }}</small>
                            </div>
                            <div class="p-3 rounded-3 bg-light text-dark shadow-sm">
                                {{ $message->message }}
                            </div>
                        </div>
                    @empty
                        <p class="text-muted text-center my-4"><i class="fas fa-envelope-open-text me-1"></i> {{ __('No messages yet.') }}</p>
                    @endforelse
                </div>
            </div>

            {{-- Status Change --}}
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.tickets.changeStatus', $ticket->id) }}">
                        @csrf
                        @method('PATCH')
                        <div class="row align-items-end g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold text-secondary">{{ __('Update Status') }}</label>
                                <select name="status" class="form-select shadow-sm">
                                    <option value="open" {{ $ticket->status == 'open' ? 'selected' : '' }}>Open</option>
                                    <option value="hold" {{ $ticket->status == 'hold' ? 'selected' : '' }}>On Hold</option>
                                    <option value="solved" {{ $ticket->status == 'solved' ? 'selected' : '' }}>Solved</option>
                                    <option value="closed" {{ $ticket->status == 'closed' ? 'selected' : '' }}>Closed</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <button type="submit" class="btn btn-purple shadow-sm mb-0 w-100">
                                    <i class="fas fa-sync-alt me-1"></i> {{ __('Update') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection


@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.getElementById('replyBtn').addEventListener('click', function() {
    Swal.fire({
        title: 'Reply to Ticket',
        input: 'textarea',
        inputLabel: 'Your Message',
        inputPlaceholder: 'Type your reply...',
        inputAttributes: { 'aria-label': 'Type your message here' },
        showCancelButton: true,
        confirmButtonText: 'Send',
        cancelButtonText: 'Cancel',
        confirmButtonColor: '#6f42c1',
        showLoaderOnConfirm: true,
        preConfirm: (message) => {
            if (!message) {
                Swal.showValidationMessage('Message cannot be empty');
                return false;
            }

            return fetch("{{ route('admin.tickets.reply', $ticket->id) }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ message })
            })
            .then(response => {
                if (!response.ok) throw new Error(response.statusText);
                return response.json();
            })
            .catch(error => {
                Swal.showValidationMessage(`Request failed: ${error}`);
            });
        },
        allowOutsideClick: () => !Swal.isLoading()
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire({
                icon: 'success',
                title: 'Reply Sent!',
                timer: 1500,
                showConfirmButton: false
            }).then(() => location.reload());
        }
    });
});
</script>
@endpush

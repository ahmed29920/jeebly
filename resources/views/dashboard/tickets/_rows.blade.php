@forelse($tickets as $ticket)
    <tr>
        <td>{{ $ticket->id }}</td>
        <td>{{ $ticket->subject }}</td>
        <td>
            <span class="badge bg-info text-uppercase">{{ $ticket->ticket_from }}</span>
        </td>
        <td>
            <span class="badge
                @if($ticket->status == 'open') bg-success
                @elseif($ticket->status == 'hold') bg-warning
                @elseif($ticket->status == 'solved') bg-primary
                @else bg-secondary @endif">
                {{ ucfirst($ticket->status) }}
            </span>
        </td>
        <td>{{ $ticket->updated_at->diffForHumans() }}</td>
        <td>
            <a href="{{ route('admin.tickets.show', $ticket->id) }}" class="btn btn-sm btn-outline-primary">
                <i class="fas fa-eye me-1"></i> {{ __('View') }}
            </a>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="6" class="text-center py-4 text-muted">{{ __('No tickets found.') }}</td>
    </tr>
@endforelse

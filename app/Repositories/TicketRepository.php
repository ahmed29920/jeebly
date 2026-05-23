<?php

namespace App\Repositories;

use App\Models\Ticket;
use App\Models\TicketMessage;
use Illuminate\Support\Facades\DB;

class TicketRepository
{
    public function filter(array $filters = [])
    {
        $query = Ticket::query()->with('user');

        if (isset($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (isset($filters['ticket_from'])) {
            $query->where('ticket_from', $filters['ticket_from']);
        }

        if (isset($filters['search'])) {
            $query->where('subject', 'like', '%' . $filters['search'] . '%');
        }

        return $query->latest()->paginate($filters['per_page'] ?? 20);
    }

    public function getUserTickets(int $userId)
    {
        return Ticket::where('user_id', $userId)
            ->with(['messages' => fn($q) => $q->latest()])
            ->latest()
            ->get();
    }

    public function findById(int $ticketId)
    {
        return Ticket::with(['user', 'messages'])->findOrFail($ticketId);
    }

    public function findByUuid(string $uuid)
    {
        return Ticket::with(['user', 'messages'])->where('uuid', $uuid)->first();
    }

    public function create(array $data): Ticket
    {
        return Ticket::create([
            'user_id'     => $data['user_id'],
            'subject'     => $data['subject'],
            'description'     => $data['description'],
            'status'      => $data['status'] ?? 'open',
            'ticket_from' => $data['ticket_from'],
            'attachments' => $data['attachments'] ?? null,
        ]);
    }

    public function update(Ticket $ticket, array $data): Ticket
    {
        $ticket->update($data);
        return $ticket;
    }

    public function delete(Ticket $ticket): bool
    {
        return $ticket->delete();
    }

    public function updateStatus(int $ticketId, string $status): Ticket
    {
        $ticket = Ticket::findOrFail($ticketId);
        $ticket->update(['status' => $status]);
        return $ticket;
    }

    public function createMessage(array $data): TicketMessage
    {
        return TicketMessage::create([
            'ticket_id'   => $data['ticket_id'],
            'sender_type' => $data['sender_type'],
            'sender_id'   => $data['sender_id'],
            'message'     => $data['message'],
        ]);
    }

    public function replyToTicket(array $data): TicketMessage
    {
        return DB::transaction(function () use ($data) {
            $message = TicketMessage::create([
                'ticket_id'   => $data['ticket_id'],
                'sender_type' => $data['sender_type'],
                'sender_id'   => $data['sender_id'],
                'message'     => $data['message'],
            ]);

            Ticket::where('id', $data['ticket_id'])
                ->update(['updated_at' => now()]);

            return $message;
        });
    }

    public function getTicketMessages(int $ticketId)
    {
        return TicketMessage::where('ticket_id', $ticketId)
            ->with('ticket')
            ->orderBy('created_at')
            ->get();
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\TicketRequest;
use App\Models\Ticket;
use App\Services\TicketService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Exception;

class TicketController extends Controller
{
    protected TicketService $ticketService;

    public function __construct(TicketService $ticketService)
    {
        $this->ticketService = $ticketService;
    }

    public function index()
    {
        try {
            $tickets = $this->ticketService->getUserTickets(Auth::id());
            return response()->json(['status' => true, 'data' => $tickets]);
        } catch (Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function store(TicketRequest $request)
    {
        $data = $request->validated();

        $ticket = $this->ticketService->create($data);

        return response()->json(['status' => true, 'data' => $ticket], 201);
    }

    public function show($id)
    {
        try {
            $ticket = $this->ticketService->getById($id);
            return response()->json(['status' => true, 'data' => $ticket]);
        } catch (Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()], 404);
        }
    }

    public function reply(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'message' => 'required|string',
            ]);

            $ticket = $this->ticketService->getById($id);

            $reply = $this->ticketService->replyToTicket([
                'ticket_id' => $ticket->id,
                'sender_type' => $ticket->ticket_from,
                'sender_id' => Auth::id(),
                'message' => $validated['message'],
            ]);

            return response()->json(['status' => true, 'data' => $reply]);
        } catch (Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function update(TicketRequest $request, Ticket $ticket){
        $data = $request->validated();
        return $this->ticketService->update($ticket , $data);
    }
    public function destroy(Ticket $ticket){
        return $this->ticketService->delete($ticket);
    }
}

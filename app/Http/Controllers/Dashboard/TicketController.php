<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Services\TicketService;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\Auth;

class TicketController extends Controller
{
    protected TicketService $ticketService;

    public function __construct(TicketService $ticketService)
    {
        $this->ticketService = $ticketService;
    }

    public function index(Request $request)
    {
        $filters = $request->only(['status', 'ticket_from', 'search']);
        $tickets = $this->ticketService->getAll($filters);

        return view('dashboard.tickets.index', compact('tickets', 'filters'));
    }

    public function filter(Request $request)
    {
        $filters = $request->only(['status', 'ticket_from', 'search']);
        $tickets = $this->ticketService->getAll($filters);

        $html = view('dashboard.tickets._rows', compact('tickets'))->render();

        return response()->json(['html' => $html]);
    }

    public function show($id)
    {
        try {
            $ticket = $this->ticketService->getById($id);
            return view('dashboard.tickets.show', compact('ticket'));
        } catch (Exception $e) {
            return redirect()->route('admin.tickets.index')->with('error', 'Ticket not found');
        }
    }

    public function reply(Request $request, $id)
    {
        $request->validate(['message' => 'required|string']);

        $reply = $this->ticketService->replyToTicket([
            'ticket_id' => $id,
            'sender_type' => 'admin',
            'sender_id' => Auth::id(),
            'message' => $request->message,
        ]);

        return response()->json([
            'sucess' => true
        ]);
    }

    public function changeStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:open,solved,closed,hold',
        ]);

        try {
            $this->ticketService->changeStatus($id, $request->status);
            return back()->with('success', 'Ticket status updated');
        } catch (Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}

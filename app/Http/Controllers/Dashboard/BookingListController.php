<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\BookingList;
use App\Services\BookingListService;
use Illuminate\Http\Request;

class BookingListController extends Controller
{
    protected $bookingListService;

    public function __construct(BookingListService $bookingListService)
    {
        $this->bookingListService = $bookingListService;
    }

    public function index()
    {
        $bookingLists = $this->bookingListService->all();
        return view('dashboard.booking-lists.index', compact('bookingLists'));
    }

    public function show($id)
    {
        $bookingList = $this->bookingListService->findById($id);
        // Relationships are already loaded in repository
        return view('dashboard.booking-lists.show', compact('bookingList'));
    }

    public function edit($id)
    {
        $bookingList = $this->bookingListService->findById($id);
        return view('dashboard.booking-lists.edit', compact('bookingList'));
    }

    public function update(Request $request, BookingList $bookingList)
    {
        $data = $request->validate([
            'expected_at' => 'required|date',
            'status' => 'required|in:pending,confirmed,cancelled,fulfilled',
        ]);

        // Convert datetime-local format to proper datetime
        if (isset($data['expected_at']) && strpos($data['expected_at'], 'T') !== false) {
            $data['expected_at'] = str_replace('T', ' ', $data['expected_at']) . ':00';
        }

        $this->bookingListService->update($bookingList, $data);

        return redirect()->route('admin.booking-lists.show', $bookingList->id)
            ->with('success', 'Booking list updated successfully');
    }
}


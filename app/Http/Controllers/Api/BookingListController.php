<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\BookingListRequest;
use App\Http\Resources\BookingListResource;
use App\Models\BookingList;
use App\Services\BookingListService;
use Illuminate\Support\Facades\Auth;

class BookingListController extends Controller
{
    protected $bookingListService;

    public function __construct(BookingListService $bookingListService)
    {
        $this->bookingListService = $bookingListService;
    }

    public function index()
    {
        $bookingLists = $this->bookingListService->findByUserId(Auth::user()->id);
        return BookingListResource::collection($bookingLists);
    }

    public function store(BookingListRequest $request)
    {
        $bookingList = $this->bookingListService->create($request->validated());
        return new BookingListResource($bookingList);
    }
    public function show(BookingList $bookingList)
    {
        $bookingList->load('product');
        return new BookingListResource($bookingList);
    }
    public function update(BookingListRequest $request, BookingList $bookingList)
    {
        $this->bookingListService->update($bookingList, $request->validated());
        return new BookingListResource($bookingList);
    }
    public function destroy(BookingList $bookingList)
    {
        $this->bookingListService->delete($bookingList);
        return response()->json(['message' => __('messages.booking_list_deleted_successfully')]);
    }
    public function cancel(BookingList $bookingList)
    {
        $this->bookingListService->update($bookingList, ['status' => 'cancelled']);
        return response()->json(['message' => __('messages.booking_list_cancelled_successfully')]);
    }
}

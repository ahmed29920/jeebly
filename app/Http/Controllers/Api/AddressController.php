<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\AddressRequest;
use App\Http\Resources\AddressResource;
use App\Models\Address;
use App\Services\AddressService;
use Illuminate\Http\Request;

class AddressController extends Controller
{
    public function __construct(
        protected AddressService $addressService
    ) {}

    public function index(Request $request)
    {
        $addresses = $this->addressService->getUserAddresses($request->user()->id);
        return AddressResource::collection($addresses);
    }

    public function store(AddressRequest $request)
    {
        $address = $this->addressService->store($request->validated(), $request->user()->id);
        return new AddressResource($address);
    }

    // public function show($id, Request $request)
    // {
    //     $address = $this->addressService->getUserAddressById($id, $request->user()->id);
    //     return new AddressResource($address);
    // }

    // public function update(AddressRequest $request, Address $address)
    // {
    //     $updated = $this->addressService->update($address, $request->validated());
    //     return new AddressResource($updated);
    // }

    public function destroy(Address $address)
    {
        $this->addressService->delete($address);
        return response()->json(['message' => __('messages.address_deleted_successfully')]);
    }
}

<?php

namespace App\Http\Controllers\Api\Delivery;

use App\Events\DeliveryLocationUpdated;
use App\Http\Controllers\Controller;
use App\Models\Delivery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redis;

class LocationController extends Controller
{
    public function updateLocation(Request $request)
    {
        $data = $request->validate([
            'lat' => 'required|numeric',
            'lng' => 'required|numeric',
        ]);

        $user = Auth::user();
        $deliveryId = $user->delivery->id;
        $delivery = Delivery::find($deliveryId);
        if (!$delivery) {
            return response()->json(['success' => false, 'message' => __('messages.delivery_not_found')], 404);
        }
        $currentOrder = $delivery->currentOrder;
        if (!$currentOrder) {
            return response()->json(['success' => false, 'message' => __('messages.order_not_found')], 404);
        }
        $data['order_id'] = $currentOrder->id;
        Redis::set('order:'.$currentOrder->id, json_encode([
            'lat' => $data['lat'],
            'lng' => $data['lng'],
            'updated_at' => now()->toDateTimeString(),
        ]));

        DeliveryLocationUpdated::dispatch($deliveryId, $currentOrder->id, $data['lat'], $data['lng']);

        return response()->json(['success' => true, 'message' => __('messages.location_updated_successfully')]);
    }
}

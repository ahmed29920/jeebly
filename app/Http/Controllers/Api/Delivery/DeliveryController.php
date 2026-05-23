<?php

namespace App\Http\Controllers\Api\Delivery;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class DeliveryController extends Controller
{
    public function setOnline(){
        $delivery = Auth::user()->delivery;
        $delivery->update(['is_online' => true]);
        return response()->json(['success' => true, 'message' => __('messages.delivery_set_online_successfully')]);
    }
    public function setOffline(){
        $delivery = Auth::user()->delivery;
        $delivery->update(['is_online' => false]);
        return response()->json(['success' => true, 'message' => __('messages.delivery_set_offline_successfully')]);
    }
}

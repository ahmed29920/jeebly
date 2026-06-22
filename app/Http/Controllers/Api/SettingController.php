<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Setting;

class SettingController extends Controller
{
    public function index()
    {
        $settings = Setting::all();

        return response()->json([
            'tik_tok_link'               => $settings->firstWhere('key', 'tik_tok_link')?->value,
            'insta_link'                 => $settings->firstWhere('key', 'insta_link')?->value,
            'fb_link'                    => $settings->firstWhere('key', 'fb_link')?->value,
            'shipping_cost_per_km'       => $settings->firstWhere('key', 'shipping_cost')?->value,
            'min_shipping_cost'          => $settings->firstWhere('key', 'min_shipping_cost')?->value,
            'service_fee'                => $settings->firstWhere('key', 'service_fee')?->value ?? 0,
            'invitation_discount_points' => $settings->firstWhere('key', 'invitation_discount_points')?->value,
            'point_to_money_rate'        => $settings->firstWhere('key', 'point_to_money_rate')?->value,
            'order_points_rate'          => $settings->firstWhere('key', 'order_points_rate')?->value,
            'inviter_order_points_rate'  => $settings->firstWhere('key', 'inviter_order_points_rate')?->value,
            'discount_sentence_en'       => $settings->firstWhere('key', 'discount_sentence_en')?->value,
            'policies'                   => Setting::policiesPayload($settings),
        ]);
    }

    public function policies()
    {
        return response()->json([
            'policies' => Setting::policiesPayload(),
        ]);
    }
}

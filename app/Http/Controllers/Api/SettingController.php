<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        $settings = Setting::all();

        return response()->json([
            'tik_tok_link'               => $settings->firstWhere('key','tik_tok_link')->value ?? null,
            'insta_link'                 => $settings->firstWhere('key','insta_link')->value ?? null,
            'fb_link'                    => $settings->firstWhere('key','fb_link')->value ?? null,
            'shipping_cost_per_km'       => $settings->firstWhere('key', 'shipping_cost')->value ?? null,
            'min_shipping_cost'          => $settings->firstWhere('key', 'min_shipping_cost')->value ?? null,
            'invitation_discount_points' => $settings->firstWhere('key', 'invitation_discount_points')->value ?? null,
            'point_to_money_rate'        =>$settings->firstWhere('key', 'point_to_money_rate')->value ?? null,
            'order_points_rate'          =>$settings->firstWhere('key', 'order_points_rate')->value ?? null,
            'inviter_order_points_rate'  =>$settings->firstWhere('key', 'inviter_order_points_rate')->value ?? null,
            'discount_sentence_en'       =>$settings->firstWhere('key', 'discount_sentence_en')->value ?? null,
        ]);
    }
}

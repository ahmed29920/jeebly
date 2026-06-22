<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        $settings = Setting::pluck('value', 'key')->toArray();
        return view('dashboard.settings.index', compact('settings'));
    }
    public function update(Request $request)
    {
        $data = $request->only(array_merge([
            'app_name',
            'invitation_discount_points',
            'shipping_cost',
            'min_shipping_cost',
            'service_fee',
            'delivery_man_calculation_method',
            'delivery_man_calculation_value',
            'allow_order_points',
            'allow_inviter_order_points',
            'order_points_rate',
            'inviter_order_points_rate',
            'point_to_money_rate',
            'max_points_discount_per_order',
            'allow_more_than_one_free_item',
            'allow_branch_admin_to_edit_stock',
        ], Setting::policySettingKeys()));

        $data['allow_order_points'] = $request->has('allow_order_points') ? 1 : 0;
        $data['allow_inviter_order_points'] = $request->has('allow_inviter_order_points') ? 1 : 0;
        $data['allow_branch_admin_to_edit_stock'] = $request->has('allow_branch_admin_to_edit_stock') ? 1 : 0;

        if ($request->hasFile('app_logo')) {
            $data['app_logo'] = $request->file('app_logo')->store('settings', 'public');
        }

        if ($request->hasFile('app_icon')) {
            $data['app_icon'] = $request->file('app_icon')->store('settings', 'public');
        }

        foreach ($data as $key => $value) {
            Setting::updateOrCreate(
                ['key' => $key],
                [
                    'value' => $value,
                    'type' => Setting::typeForKey($key),
                ]
            );
        }

        cache()->forget('settings.all');

        return back()->with('success', 'Settings updated successfully!');
    }
}

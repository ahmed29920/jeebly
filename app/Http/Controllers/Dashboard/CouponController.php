<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Web\Dashboard\CouponRequest;
use App\Models\Coupon;
use App\Services\CouponService;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    public function __construct(
        protected CouponService $couponService
    ) {}

    public function index()
    {
        $coupons = $this->couponService->all();
        return view('dashboard.coupons.index', compact('coupons'));
    }

    public function show(Coupon $coupon)
    {
        return view('dashboard.coupons.show', compact('coupon'));
    }

    public function create()
    {
        return view('dashboard.coupons.create');
    }

    public function store(CouponRequest $request)
    {
        $this->couponService->store($request->validated());

        return redirect()->route('admin.coupons.index')->with('success', 'Coupon created successfully.');
    }

    public function edit(Coupon $coupon)
    {
        return view('dashboard.coupons.edit', compact('coupon'));
    }
    public function update(CouponRequest $request, Coupon $coupon)
    {
        $this->couponService->update($coupon, $request->validated());

        return redirect()->route('admin.coupons.index')->with('success', 'Coupon updated successfully.');
    }

    public function destroy(Coupon $coupon)
    {
        $this->couponService->delete($coupon);

        if (request()->ajax()) {
            return response()->json([
                'status'  => 'success',
                'message' => 'Coupon deleted successfully!',
            ]);
        }

        return redirect()->route('admin.categories.index')->with('success', 'Coupon deleted successfully!');
    }
    public function bulk(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'action' => 'required|string|in:delete,activate,deactivate',
        ]);

        $this->couponService->bulkAction($request->ids, $request->action);

        return redirect()->back()->with('success', 'Bulk action applied successfully.');
    }
}

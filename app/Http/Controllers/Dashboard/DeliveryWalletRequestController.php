<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\DeliveryWalletRequest;
use App\Services\DeliveryWalletRequestService;
use Illuminate\Http\Request;

class DeliveryWalletRequestController extends Controller
{
    public function __construct(protected DeliveryWalletRequestService $service)
    {
    }

    public function index()
    {
        $requests = $this->service->allWithRelations();

        return view('dashboard.delivery-wallet-requests.index', compact('requests'));
    }

    public function update(Request $request, DeliveryWalletRequest $deliveryWalletRequest)
    {
        $data = $request->validate([
            'status'      => 'required|in:approved,rejected',
            'admin_notes' => 'nullable|string|max:1000',
        ]);

        $result = $this->service->updateStatus(
            $deliveryWalletRequest,
            $data['status'],
            $data['admin_notes'] ?? null,
        );

        return redirect()->back()->with($result['success'] ? 'success' : 'error', $result['message']);
    }
}

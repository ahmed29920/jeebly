<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Web\Dashboard\SendAdminNotificationRequest;
use App\Models\Branch;
use App\Models\Delivery;
use App\Models\User;
use App\Services\AdminNotificationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AdminNotificationController extends Controller
{
    public function __construct(
        protected AdminNotificationService $service
    ) {}

    public function create(): View
    {
        $users = User::query()
            ->where('role', 'user')
            ->select(['id', 'name', 'email', 'phone'])
            ->orderByDesc('id')
            ->limit(5000)
            ->get();

        $branches = Branch::query()
            ->orderByDesc('id')
            ->get(['id', 'name']);

        $deliveries = Delivery::query()
            ->with(['user:id,name,phone,email'])
            ->orderByDesc('id')
            ->limit(5000)
            ->get();

        return view('dashboard.notifications.create', compact('users', 'branches', 'deliveries'));
    }

    public function store(SendAdminNotificationRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $recipientType = (string) $validated['recipient_type'];
        $audience = (string) $validated['audience'];
        $sendInApp = (bool) ($validated['send_in_app'] ?? false);
        $sendPush = (bool) ($validated['send_push'] ?? false);

        $ids = match ($recipientType) {
            'branches' => array_values(array_filter($validated['branch_ids'] ?? [], fn ($v) => $v !== null && $v !== '')),
            'deliveries' => array_values(array_filter($validated['delivery_ids'] ?? [], fn ($v) => $v !== null && $v !== '')),
            default => array_values(array_filter($validated['user_ids'] ?? [], fn ($v) => $v !== null && $v !== '')),
        };

        $data = [];
        $dataJson = $validated['data_json'] ?? null;
        if (is_string($dataJson) && trim($dataJson) !== '') {
            $decoded = json_decode($dataJson, true);
            $data = is_array($decoded) ? $decoded : [];
        }

        $data += [
            'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
            'recipient_type' => $recipientType,
        ];

        $title = (string) $validated['title'];
        $body = (string) $validated['body'];
        $sentByUserId = Auth::id();

        $dbCount = 0;
        $pushCount = 0;
        $recipientCount = 0;

        $buffer = new Collection();
        $bufferSize = 200;

        foreach ($this->service->recipientsByAudience($recipientType, $audience, $ids) as $user) {
            $buffer->push($user);
            $recipientCount++;

            if ($buffer->count() >= $bufferSize) {
                $result = $this->service->sendToUsers(
                    $buffer,
                    $title,
                    $body,
                    $data,
                    $sentByUserId,
                    $sendInApp,
                    $sendPush,
                );
                $dbCount += $result['database'];
                $pushCount += $result['push'];
                $buffer = new Collection();
            }
        }

        if ($buffer->count() > 0) {
            $result = $this->service->sendToUsers(
                $buffer,
                $title,
                $body,
                $data,
                $sentByUserId,
                $sendInApp,
                $sendPush,
            );
            $dbCount += $result['database'];
            $pushCount += $result['push'];
        }

        if ($recipientCount === 0) {
            return back()->withInput()->with('error', __('No recipients found for the selected audience.'));
        }

        return back()->with('success', __('Notification sent to :count recipient(s). In-app: :db, Push: :push', [
            'count' => $recipientCount,
            'db' => $dbCount,
            'push' => $pushCount,
        ]));
    }
}

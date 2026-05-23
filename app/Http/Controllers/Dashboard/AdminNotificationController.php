<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Web\Dashboard\SendAdminNotificationRequest;
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

        return view('dashboard.notifications.create', compact('users'));
    }

    public function store(SendAdminNotificationRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $audience = (string) $validated['audience'];
        $userIds = array_values(array_filter($validated['user_ids'] ?? [], fn ($v) => $v !== null && $v !== ''));

        $data = [];
        $dataJson = $validated['data_json'] ?? null;
        if (is_string($dataJson) && trim($dataJson) !== '') {
            $decoded = json_decode($dataJson, true);
            $data = is_array($decoded) ? $decoded : [];
        }

        // Helpful default for Flutter clients, if not provided.
        $data += ['click_action' => 'FLUTTER_NOTIFICATION_CLICK'];

        $title = (string) $validated['title'];
        $body = (string) $validated['body'];
        $sentByUserId = Auth::id();

        $dbCount = 0;
        $pushCount = 0;

        $buffer = new Collection();
        $bufferSize = 200;

        foreach ($this->service->usersByAudience($audience, $userIds) as $user) {
            $buffer->push($user);
            if ($buffer->count() >= $bufferSize) {
                $result = $this->service->sendToUsers($buffer, $title, $body, $data, $sentByUserId);
                $dbCount += $result['database'];
                $pushCount += $result['push'];
                $buffer = new Collection();
            }
        }

        if ($buffer->count() > 0) {
            $result = $this->service->sendToUsers($buffer, $title, $body, $data, $sentByUserId);
            $dbCount += $result['database'];
            $pushCount += $result['push'];
        }

        return back()->with('success', __('Notification sent. Database: :db, Push: :push', [
            'db' => $dbCount,
            'push' => $pushCount,
        ]));
    }
}


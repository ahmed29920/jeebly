<?php

namespace App\Http\Controllers;

use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;
use Kreait\Laravel\Firebase\Facades\Firebase;

class NotificationController extends Controller
{
    public function test()
    {
        $messaging = Firebase::messaging();
        $token = 'cev-DyK-QOKUJ6iNwO5NHf:APA91bFndFgZ2bDIellohZdnz_2yN7QaThBl5QOgstl_FMuqZ3ieq2d6qXTkl3kVUQZfpwPZivgCldIrzH12Qm7z1E5tyniS43tjAJuokzUa-maljaMwNBk';
       $message = CloudMessage::new()
            ->toToken($token)
            ->withNotification(
                Notification::create(
                    'Testing!',
                    'Firebase Laravel works!'
                )
            )
            ->withData([
                'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
                'custom_key' => 'custom_value'
            ]);

        $result = $messaging->send($message);

        dd($result);
    }
}

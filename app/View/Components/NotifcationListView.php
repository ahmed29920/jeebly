<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class NotifcationListView extends Component
{
    /**
     * Create a new component instance.
     */
    public $notifications;

    public function __construct()
    {
        $this->notifications = auth()->user()->unreadNotifications->take(5);
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.notifcation-list-view');
    }
}

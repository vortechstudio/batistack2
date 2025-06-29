<?php

declare(strict_types=1);

namespace App\Livewire\Core\Block;

use Livewire\Component;

final class Notifications extends Component
{
    public $notifications;

    public string $icon;

    public function mount(): void
    {
        $this->notifications = auth()->user()->unreadNotifications;
    }

    public function readNotification(): void
    {
        auth()->user()->unreadNotifications->markAsRead();
    }

    public function render()
    {
        return view('livewire.core.block.notifications');
    }

    public function getIconNotification($id): string
    {
        $notif = $this->notifications->where('id', $id)->first();

        return $notif['data']['icon'];
    }
}

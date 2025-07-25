<?php

declare(strict_types=1);

namespace App\Notifications\Core\Bank;

use App\Models\Core\CompanyBankAccount;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

final class UpdateBankAccount extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        public CompanyBankAccount $account,
    ) {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->line('The introduction to the notification.')
            ->action('Notification Action', url('/'))
            ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'green',
            'icon' => 'heroicon-o-arrows-right-left',
            'title' => 'Compte '.$this->account->name,
            'description' => "Les mouvements du comptes {$this->account->name} ont été mise à jours",
        ];
    }
}

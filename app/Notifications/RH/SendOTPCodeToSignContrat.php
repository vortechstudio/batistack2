<?php

declare(strict_types=1);

namespace App\Notifications\RH;

use App\Models\RH\EmployeContrat;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

final class SendOTPCodeToSignContrat extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        public EmployeContrat $employeContrat
    ) {}

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Signature de votre contrat de travail')
            ->greeting('Bonjour '.$this->employeContrat->employe->user->name)
            ->line('Afin de signer numériquement votre contrat de travail, Voici le code OTP permettant sa signature:')
            ->line($this->employeContrat->signed_code_otp)
            ->line('Nous vous rappelons que la signature du contrat de travail doit survenir avant le '.$this->employeContrat->signed_start_at->addDays(3)->format('d/m/Y à H:i'))
            ->line("Passer ce délai, un nouveau contrat de travail devra être établie, ce qui survient uniquement après un nouvelle entretien préalable à l'embauche.")
            ->salutation('Cordialement,');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}

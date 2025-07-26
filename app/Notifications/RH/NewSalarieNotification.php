<?php

declare(strict_types=1);

namespace App\Notifications\RH;

use App\Models\Core\Company;
use App\Models\RH\Employe;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

final class NewSalarieNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(public Employe $salarie, public string $password)
    {
        //
    }

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
            ->subject('Bienvenue chez '.Company::first()->name)
            ->greeting('Bonjour '.$this->salarie->full_name)
            ->line('Nous avons le plaisir de vous informer que votre compte salarié a été créé avec succès.')
            ->line("Vous pouvez dès à présent accéder à votre espace personnel en vous connectant à l'adresse suivante :")
            ->action('Espace salarié', url('https://portail.'.config('batistack.domain')))
            ->line('Votre identifiant est votre adresse mail et le mot de passe par default est:'.$this->password)
            ->line('Dans un souci de sécurité evidente, nous vous demandons de changer ce mot de passe immédiatement après connexion.')
            ->line("Votre contrat de travail est disponible dans l’onglet 'Documents'. Nous vous invitons à le consulter attentivement et à procéder à sa signature dans les plus brefs délais si ce n’est pas déjà fait.")
            ->line('Si vous rencontrez la moindre difficulté pour vous connecter ou pour accéder à vos documents, n’hésitez pas à revenir vers nous.')
            ->line("Bienvenue dans l'équipe,")
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

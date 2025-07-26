<?php

declare(strict_types=1);

namespace App\Channels;

use App\Channels\Messages\WhatsAppMessage;
use Exception;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Log;
use Twilio\Exceptions\TwilioException;
use Twilio\Rest\Api\V2010\Account\MessageInstance;
use Twilio\Rest\Client;

// @codeCoverageIgnoreStart
final class WhatAppChannel
{
    public function send($notifiable, Notification $notification): MessageInstance|false
    {
        $message = $this->getMessageContent($notification, $notifiable);

        $to = $notifiable->routeNotificationFor('WhatsApp');
        $from = config('services.twillio.whatsapp_from');

        if (empty($to)) {
            Log::warning('WhatsApp notification failed: No phone number for notifiable', [
                'notifiable_id' => $notifiable->id ?? null,
                'notification' => get_class($notification),
            ]);

            return false;
        }

        $twilio = new Client(config('services.twillio.sid'), config('services.twillio.token'));

        try {
            return $twilio->messages->create('whatsapp:'.$to, [
                'from' => 'whatsapp:'.$from,
                'body' => $message,
            ]);
        } catch (TwilioException $e) {
            Log::emergency('WhatsApp notification failed', [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'message' => $e->getMessage(),
                'to' => $to,
                'notification' => get_class($notification),
            ]);

            return false;
        }
    }

    /**
     * Extract message content from notification
     */
    private function getMessageContent(Notification $notification, $notifiable): string
    {
        // Priorité 1: Méthode toWhatsApp() si elle existe
        if (method_exists($notification, 'toWhatsApp')) {
            $result = $notification->toWhatsApp($notifiable);

            if ($result instanceof WhatsAppMessage) {
                return $result->content;
            }

            if (is_string($result)) {
                return $result;
            }
        }

        // Priorité 2: Extraire du contenu mail
        if (method_exists($notification, 'toMail')) {
            try {
                $mailMessage = $notification->toMail($notifiable);
                if ($mailMessage instanceof MailMessage) {
                    // Combiner les lignes d'introduction
                    $content = implode(' ', $mailMessage->introLines);

                    return ! empty($content) ? $content : 'Nouvelle notification';
                }
            } catch (Exception $e) {
                Log::warning('Failed to extract mail content for WhatsApp', [
                    'notification' => get_class($notification),
                    'error' => $e->getMessage(),
                ]);
            }
        }

        // Priorité 3: Utiliser toArray()
        if (method_exists($notification, 'toArray')) {
            $array = $notification->toArray($notifiable);
            if (isset($array['message'])) {
                return $array['message'];
            }
            if (isset($array['content'])) {
                return $array['content'];
            }
        }

        // Fallback par défaut
        return 'Vous avez reçu une nouvelle notification.';
    }
}
// @codeCoverageIgnoreEnd

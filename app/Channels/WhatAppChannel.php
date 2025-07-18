<?php

declare(strict_types=1);

namespace App\Channels;

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
        $message = $notification->toWhatsApp($notifiable);

        $to = $notifiable->routeNotificationFor('WhatsApp');
        $from = config('services.twillio.whatsapp_from');

        $twilio = new Client(config('services.twillio.sid'), config('services.twillio.token'));

        try {
            return $twilio->messages->create('whatsapp:'.$to, [
                'from' => 'whatsapp:'.$from,
                'body' => $message,
            ]);
        } catch (TwilioException $e) {
            Log::emergency('File:'.$e->getFile().'Line:'.$e->getLine().'Message:'.$e->getMessage());

            return false;
        }
    }
}
// @codeCoverageIgnoreEnd

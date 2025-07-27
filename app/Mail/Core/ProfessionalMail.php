<?php

declare(strict_types=1);

namespace App\Mail\Core;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

final class ProfessionalMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(
        public string $emailSubject,
        public string $content,
        public ?string $greeting = null,
        public ?string $actionText = null,
        public ?string $actionUrl = null,
        public ?string $additionalInfo = null,
        public ?string $recipientEmail = null,
        public ?string $recipientName = null,
        public ?array $emailAttachments = null,
    ) {
        //
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $company = \App\Models\Core\Company::first();

        return new Envelope(
            from: new Address(
                $company?->email ?? config('mail.from.address'),
                $company?->name ?? config('mail.from.name')
            ),
            subject: $this->emailSubject,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'mail.core.professional-message',
            with: [
                'subject' => $this->emailSubject,
                'content' => $this->content,
                'greeting' => $this->greeting,
                'actionText' => $this->actionText,
                'actionUrl' => $this->actionUrl,
                'additionalInfo' => $this->additionalInfo,
            ]
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        $attachments = [];
        if ($this->emailAttachments) {
            foreach ($this->emailAttachments as $key => $value) {
                $attachments[] = Attachment::fromPath($value)
                    ->as($key);
            }
        }
        return $attachments;
    }
}

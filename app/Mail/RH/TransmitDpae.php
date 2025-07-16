<?php

namespace App\Mail\RH;

use App\Models\RH\Employe;
use Auth;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Storage;

class TransmitDpae extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(
        public Employe $salarie,
        public string $dpaePath
    )
    {
        //
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Transmit Dpae',
            from: new Address(config('mail.from.address'), config('mail.from.name')),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'mail.rh.transmit-dpae',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [
            Attachment::fromPath($this->dpaePath),
            Attachment::fromPath(Storage::disk('public')->path('rh/salarie/'.$this->salarie->id.'/documents/cni-recto.jpg')),
            Attachment::fromPath(Storage::disk('public')->path('rh/salarie/'.$this->salarie->id.'/documents/cni-verso.jpg')),
            Attachment::fromPath(Storage::disk('public')->path('rh/salarie/'.$this->salarie->id.'/documents/carte-vital.jpg')),
            Attachment::fromPath(Storage::disk('public')->path('rh/salarie/'.$this->salarie->id.'/documents/rib.jpg')),
        ];
    }
}

<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class DailySolarMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct()
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Daily Solar - Update',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.daily-solar',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}

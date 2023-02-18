<?php

namespace App\Mail;

use App\Models\Estimate;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class EmailDailySolarMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Estimate $estimate)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Daily Solar Estimate: ' . number_format($this->estimate->today_watt_hours_day / 1000,2) . ' KwH',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.email-daily-solar',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}

<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class EngagementLetter extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $letterContent,
        public string $companyName
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Engagement Letter - ' . $this->companyName,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.engagement-letter-email',
            with: [
                'companyName' => $this->companyName,
            ]
        );
    }

    public function attachments(): array
    {
        // Return empty array - no attachments
        return [];
    }
}
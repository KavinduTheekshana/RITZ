<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Attachment;
use Barryvdh\DomPDF\Facade\Pdf;

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
        );
    }

    public function attachments(): array
    {
        $pdf = Pdf::loadView('pdfs.engagement-letter', [
            'letterContent' => $this->letterContent
        ]);

        return [
            Attachment::fromData(fn () => $pdf->output(), 'engagement-letter.pdf')
                ->withMime('application/pdf'),
        ];
    }
}
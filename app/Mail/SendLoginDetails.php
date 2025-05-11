<?php

namespace App\Mail;

use App\Models\Client;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendLoginDetails extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(
        public Client $client,
        public string $password
    ) {}

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Your Login Details')
            ->markdown('emails.login-details', [
                'client' => $this->client,
                'password' => $this->password,
                'url' => route('client.login') // Adjust to your login route
            ]);
    }
}
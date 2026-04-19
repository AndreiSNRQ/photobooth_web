<?php

namespace App\Mail;

use App\Models\PhotoSession;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class GalleryLinkMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public PhotoSession $session) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '📸 Your Photobooth Gallery is Ready!',
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.gallery-link',
            with: [
                'session'    => $this->session,
                'galleryUrl' => url("/gallery/{$this->session->session_id}"),
                'name'       => $this->session->customer_name ?? 'there',
            ],
        );
    }
}

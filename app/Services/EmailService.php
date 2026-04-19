<?php

namespace App\Services;

use App\Mail\GalleryLinkMail;
use App\Models\PhotoSession;
use Illuminate\Support\Facades\Mail;

class EmailService
{
    public function sendGalleryLink(PhotoSession $session): bool
    {
        if (!$session->customer_email) {
            return false;
        }

        Mail::to($session->customer_email)->send(new GalleryLinkMail($session));
        $session->update(['email_sent' => true]);

        return true;
    }
}

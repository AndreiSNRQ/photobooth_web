<?php

namespace App\Services;

use App\Models\PhotoSession;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class QrService
{
    public function generate(PhotoSession $session): string
    {
        $galleryUrl = url("/gallery/{$session->session_id}");

        // GD extension required for PNG QR — skip gracefully if missing
        if (!extension_loaded('gd')) {
            // Return a QR API URL as fallback (rendered client-side by the kiosk/PWA anyway)
            $qrUrl = "https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=" . urlencode($galleryUrl);
            $session->update(['qr_code_url' => $qrUrl]);
            return $qrUrl;
        }

        $folder   = "sessions/{$session->session_id}";
        $filename = 'qr_code.png';
        $fullPath = storage_path("app/public/{$folder}/{$filename}");

        if (!file_exists(dirname($fullPath))) {
            mkdir(dirname($fullPath), 0755, true);
        }

        QrCode::format('png')
            ->size(300)
            ->errorCorrection('H')
            ->generate($galleryUrl, $fullPath);

        $qrUrl = Storage::disk('public')->url("{$folder}/{$filename}");
        $session->update(['qr_code_url' => $qrUrl]);

        return $qrUrl;
    }
}

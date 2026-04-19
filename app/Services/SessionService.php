<?php

namespace App\Services;

use App\Models\PhotoSession;
use Illuminate\Support\Str;

class SessionService
{
    public function create(array $data = []): PhotoSession
    {
        $sessionId = strtoupper(Str::random(12));

        $session = PhotoSession::create([
            'session_id'     => $sessionId,
            'template'       => $data['template'] ?? null,
            'total_shots'    => $data['total_shots'] ?? 4,
            'customer_email' => $data['customer_email'] ?? null,
            'customer_name'  => $data['customer_name'] ?? null,
            'status'         => 'active',
            'gallery_url'    => url("/gallery/{$sessionId}"),
            'expires_at'     => now()->addHours((int) config('photobooth.session_expiry_hours', 24)),
        ]);

        return $session;
    }

    public function findBySessionId(string $sessionId): ?PhotoSession
    {
        return PhotoSession::where('session_id', $sessionId)->first();
    }

    public function complete(PhotoSession $session): void
    {
        $session->update(['status' => 'completed']);
    }

    public function expire(PhotoSession $session): void
    {
        $session->update(['status' => 'expired']);
    }
}

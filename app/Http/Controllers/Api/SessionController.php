<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\EmailService;
use App\Services\QrService;
use App\Services\SessionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SessionController extends Controller
{
    public function __construct(
        protected SessionService $sessionService,
        protected QrService $qrService,
        protected EmailService $emailService,
    ) {}

    public function create(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'template'       => 'nullable|string|max:100',
            'total_shots'    => 'nullable|integer|min:1|max:10',
            'customer_email' => 'nullable|email',
            'customer_name'  => 'nullable|string|max:100',
        ]);

        $session = $this->sessionService->create($validated);
        $qrUrl   = $this->qrService->generate($session);

        return response()->json([
            'success'    => true,
            'session_id' => $session->session_id,
            'gallery_url'=> $session->gallery_url,
            'qr_code_url'=> $qrUrl,
        ], 201);
    }

    public function show(string $sessionId): JsonResponse
    {
        $session = $this->sessionService->findBySessionId($sessionId);

        if (!$session) {
            return response()->json(['success' => false, 'message' => 'Session not found.'], 404);
        }

        return response()->json([
            'success' => true,
            'session' => $session->load(['photos', 'payment']),
        ]);
    }

    public function complete(string $sessionId): JsonResponse
    {
        $session = $this->sessionService->findBySessionId($sessionId);

        if (!$session) {
            return response()->json(['success' => false, 'message' => 'Session not found.'], 404);
        }

        $this->sessionService->complete($session);

        // Send email if customer email is set
        if ($session->customer_email) {
            $this->emailService->sendGalleryLink($session);
        }

        return response()->json(['success' => true, 'message' => 'Session completed.']);
    }
}

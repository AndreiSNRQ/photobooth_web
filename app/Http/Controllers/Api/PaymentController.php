<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Services\SessionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PaymentController extends Controller
{
    public function __construct(protected SessionService $sessionService) {}

    public function create(Request $request, string $sessionId): JsonResponse
    {
        $session = $this->sessionService->findBySessionId($sessionId);

        if (!$session) {
            return response()->json(['success' => false, 'message' => 'Session not found.'], 404);
        }

        $validated = $request->validate([
            'amount'       => 'required|numeric|min:0',
            'method'       => 'required|in:cash,gcash,paypal',
            'payment_type' => 'required|in:pay_now,pay_later',
        ]);

        $payment = Payment::create([
            'photo_session_id' => $session->id,
            'reference_number' => 'PAY-' . strtoupper(Str::random(10)),
            'amount'           => $validated['amount'],
            'method'           => $validated['method'],
            'payment_type'     => $validated['payment_type'],
            'status'           => 'pending',
        ]);

        return response()->json([
            'success'  => true,
            'payment'  => $payment,
        ], 201);
    }

    public function confirm(Request $request, string $sessionId): JsonResponse
    {
        $session = $this->sessionService->findBySessionId($sessionId);

        if (!$session || !$session->payment) {
            return response()->json(['success' => false, 'message' => 'Payment not found.'], 404);
        }

        $request->validate([
            'metadata' => 'nullable|array',
        ]);

        $session->payment->update([
            'status'   => 'paid',
            'paid_at'  => now(),
            'metadata' => $request->input('metadata'),
        ]);

        return response()->json(['success' => true, 'message' => 'Payment confirmed.']);
    }

    public function status(string $sessionId): JsonResponse
    {
        $session = $this->sessionService->findBySessionId($sessionId);

        if (!$session) {
            return response()->json(['success' => false, 'message' => 'Session not found.'], 404);
        }

        return response()->json([
            'success' => true,
            'payment' => $session->payment,
        ]);
    }
}

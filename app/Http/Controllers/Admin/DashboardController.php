<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Photo;
use App\Models\PhotoSession;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $stats = [
            'total_sessions'   => PhotoSession::count(),
            'active_sessions'  => PhotoSession::where('status', 'active')->count(),
            'completed'        => PhotoSession::where('status', 'completed')->count(),
            'total_photos'     => Photo::count(),
            'total_revenue'    => Payment::where('status', 'paid')->sum('amount'),
            'pending_payments' => Payment::where('status', 'pending')->count(),
        ];

        $recentSessions = PhotoSession::with('payment')
            ->latest()
            ->take(10)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentSessions'));
    }

    public function sessions(Request $request): View
    {
        $query = PhotoSession::with(['photos', 'payment'])->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('session_id', 'like', "%{$request->search}%")
                  ->orWhere('customer_email', 'like', "%{$request->search}%")
                  ->orWhere('customer_name', 'like', "%{$request->search}%");
            });
        }

        $sessions = $query->paginate(20);

        return view('admin.sessions', compact('sessions'));
    }

    public function sessionDetail(string $sessionId): View
    {
        $session = PhotoSession::where('session_id', $sessionId)
            ->with(['photos', 'payment'])
            ->firstOrFail();

        return view('admin.session-detail', compact('session'));
    }

    public function deleteSession(string $sessionId)
    {
        $session = PhotoSession::where('session_id', $sessionId)->firstOrFail();
        $session->delete();

        return redirect()->route('admin.sessions')->with('success', 'Session deleted.');
    }

    public function payments(Request $request): View
    {
        $payments = Payment::with('session')->latest()->paginate(20);

        return view('admin.payments', compact('payments'));
    }
}

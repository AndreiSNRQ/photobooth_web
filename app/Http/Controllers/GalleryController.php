<?php

namespace App\Http\Controllers;

use App\Models\PhotoSession;
use Illuminate\View\View;

class GalleryController extends Controller
{
    public function show(string $sessionId): View|\Illuminate\Http\RedirectResponse
    {
        $session = PhotoSession::where('session_id', $sessionId)
            ->with(['photos' => fn($q) => $q->orderBy('shot_number')])
            ->first();

        if (!$session) {
            abort(404, 'Gallery not found.');
        }

        if ($session->isExpired()) {
            return view('gallery.expired', compact('session'));
        }

        $collage = $session->photos->firstWhere('is_collage', true);
        $shots   = $session->photos->where('is_collage', false)->values();

        return view('gallery.show', compact('session', 'collage', 'shots'));
    }
}

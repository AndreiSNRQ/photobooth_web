<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\ImageStorageService;
use App\Services\SessionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PhotoController extends Controller
{
    public function __construct(
        protected SessionService $sessionService,
        protected ImageStorageService $storageService,
    ) {}

    public function upload(Request $request, string $sessionId): JsonResponse
    {
        $session = $this->sessionService->findBySessionId($sessionId);

        if (!$session) {
            return response()->json(['success' => false, 'message' => 'Session not found.'], 404);
        }

        $request->validate([
            'photo'       => 'required|file|mimes:jpeg,jpg,png,webp|max:10240',
            'shot_number' => 'nullable|integer|min:1',
            'is_collage'  => 'nullable|boolean',
        ]);

        $photo = $this->storageService->store(
            $request->file('photo'),
            $session,
            $request->input('shot_number', 1),
            $request->boolean('is_collage', false),
        );

        return response()->json([
            'success' => true,
            'photo'   => $photo,
        ], 201);
    }

    public function index(string $sessionId): JsonResponse
    {
        $session = $this->sessionService->findBySessionId($sessionId);

        if (!$session) {
            return response()->json(['success' => false, 'message' => 'Session not found.'], 404);
        }

        return response()->json([
            'success' => true,
            'photos'  => $session->photos()->orderBy('shot_number')->get(),
        ]);
    }
}

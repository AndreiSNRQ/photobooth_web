<?php

namespace App\Services;

use App\Models\Photo;
use App\Models\PhotoSession;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class ImageStorageService
{
    protected string $driver;

    public function __construct()
    {
        $this->driver = config('photobooth.storage_driver', 'local');
    }

    public function store(UploadedFile $file, PhotoSession $session, int $shotNumber = 1, bool $isCollage = false): Photo
    {
        return match ($this->driver) {
            'cloudinary' => $this->storeCloudinary($file, $session, $shotNumber, $isCollage),
            default      => $this->storeLocal($file, $session, $shotNumber, $isCollage),
        };
    }

    protected function storeLocal(UploadedFile $file, PhotoSession $session, int $shotNumber, bool $isCollage): Photo
    {
        $folder   = "sessions/{$session->session_id}";
        $filename = ($isCollage ? 'collage' : "shot_{$shotNumber}") . '_' . time() . '.' . $file->getClientOriginalExtension();
        $path     = $file->storeAs($folder, $filename, 'public');

        return Photo::create([
            'photo_session_id' => $session->id,
            'filename'         => $filename,
            'path'             => $path,
            'url'              => Storage::disk('public')->url($path),
            'storage_driver'   => 'local',
            'shot_number'      => $shotNumber,
            'is_collage'       => $isCollage,
        ]);
    }

    protected function storeCloudinary(UploadedFile $file, PhotoSession $session, int $shotNumber, bool $isCollage): Photo
    {
        $folder   = "photobooth/{$session->session_id}";
        $publicId = ($isCollage ? 'collage' : "shot_{$shotNumber}") . '_' . time();

        $result = Cloudinary::upload($file->getRealPath(), [
            'folder'    => $folder,
            'public_id' => $publicId,
        ]);

        return Photo::create([
            'photo_session_id' => $session->id,
            'filename'         => $publicId,
            'path'             => $result->getPublicId(),
            'url'              => $result->getSecurePath(),
            'storage_driver'   => 'cloudinary',
            'public_id'        => $result->getPublicId(),
            'shot_number'      => $shotNumber,
            'is_collage'       => $isCollage,
        ]);
    }

    public function deleteSessionPhotos(PhotoSession $session): void
    {
        foreach ($session->photos as $photo) {
            if ($photo->storage_driver === 'cloudinary' && $photo->public_id) {
                Cloudinary::destroy($photo->public_id);
            } elseif ($photo->storage_driver === 'local') {
                Storage::disk('public')->delete($photo->path);
            }
            $photo->delete();
        }
    }
}

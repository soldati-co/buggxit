<?php

namespace App\Services;

use App\Models\Image;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use InvalidArgumentException;

/**
 * Single write path for the base64-in-Postgres image storage scheme used by
 * Dress and HeroSlide. ImageController::show() always base64_decode()s
 * `image_data`, so every write here must base64_encode() first — that
 * invariant is enforced in exactly one place instead of being duplicated
 * (and, in one historical case, forgotten) at each call site.
 */
class ImageStorageService
{
    private const ALLOWED_MIMETYPES = [
        'image/jpeg',
        'image/png',
        'image/gif',
        'image/webp',
        'image/bmp',
        'image/avif',
    ];

    /**
     * Store an uploaded file.
     */
    public function store(Model $imageable, UploadedFile $file, string $collection, int $sortOrder = 0): Image
    {
        $mime = $file->getMimeType();

        if (! in_array($mime, self::ALLOWED_MIMETYPES, true)) {
            throw new InvalidArgumentException("Unsupported image type: {$mime}");
        }

        return $this->relation($imageable)->create([
            'image_data' => base64_encode(file_get_contents($file->getRealPath())),
            'image_mime' => $mime,
            'collection' => $collection,
            'sort_order' => $sortOrder,
        ]);
    }

    /**
     * Store already-in-memory binary bytes (e.g. Intervention's encoded
     * output), which have no UploadedFile/mime-detection to validate against
     * the allow-list — the caller is responsible for the mime it passes.
     */
    public function storeFromBinary(Model $imageable, string $binaryData, string $mime, string $collection, int $sortOrder = 0): Image
    {
        return $this->relation($imageable)->create([
            'image_data' => base64_encode($binaryData),
            'image_mime' => $mime,
            'collection' => $collection,
            'sort_order' => $sortOrder,
        ]);
    }

    private function relation(Model $imageable)
    {
        return $imageable->morphMany(Image::class, 'imageable');
    }
}

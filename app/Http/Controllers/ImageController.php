<?php

namespace App\Http\Controllers;

use App\Models\Image;
use Illuminate\Http\Request;

class ImageController extends Controller
{
    /**
     * Display the specified image (binary stream).
     */
    public function show($id)
    {
        $image = Image::findOrFail($id);

        if (empty($image->image_data)) {
            abort(404, 'Image data is empty.');
        }

        // Create a response with raw binary data
        $response = response($image->image_data)
            ->header('Content-Type', $image->image_mime)
            ->header('Cache-Control', 'public, max-age=604800, immutable')  // Cache for 1 week
            ->header('ETag', md5($image->image_data));

        return $response;
    }
}
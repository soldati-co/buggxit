<?php

namespace App\Http\Controllers;

use App\Models\Image;
use Illuminate\Http\Request;

class ImageController extends Controller
{
    public function show($id)
    {
        $image = Image::findOrFail($id);

        if (empty($image->image_data)) {
            abort(404);
        }

        return response($image->image_data)
            ->header('Content-Type', $image->image_mime)
            ->header('X-Content-Type-Options', 'nosniff')
            ->header('Content-Disposition', 'inline')
            ->header('Cache-Control', 'public, max-age=31536000'); // 1 year
    }
}
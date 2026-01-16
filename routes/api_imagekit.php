<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Services\ImageKitService;

Route::post('/upload-image', function(Request $request, ImageKitService $imageKit) {
    try {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:10240',
            'folder' => 'nullable|string'
        ]);

        $folder = $request->input('folder', 'products');
        $result = $imageKit->uploadCategoryImage($request->file('image'), $folder);

        if ($result && $result['success']) {
            return response()->json([
                'success' => true,
                'url' => $result['url'],
                'fileId' => $result['file_id'],
                'size' => $result['size'],
                'original_size' => $result['original_size'],
                'width' => $result['width'],
                'height' => $result['height'],
                'name' => $result['name'],
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Failed to upload image'
        ], 500);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => $e->getMessage()
        ], 500);
    }
})->name('api.upload.image');
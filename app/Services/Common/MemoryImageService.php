<?php

namespace App\Services\Common;

use App\Services\Common\Contracts\ImageServiceInterface;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;

class MemoryImageService implements ImageServiceInterface
{
    /**
     * Upload an image or array of images to the specified path.
     *
     * @param UploadedFile|array $images
     * @param string $path
     * @return string|array
     */
    public function uploadImage(UploadedFile|array $images, string $path): string | array
    {

        if (is_array($images)) {
            $uploadedImages = [];
            foreach ($images as $image) {
                $uploadedImages[] = $this->uploadSingleImage($image, $path);
            }
            return $uploadedImages;
        }

        return $this->uploadSingleImage($images, $path);
    }

    /**
     * Upload a single image to the specified path using the public disk.
     *
     * @param UploadedFile $image
     * @param string $path
     * @return string
     */
    private function uploadSingleImage(UploadedFile $image, string $path): string
    {
        // Generate a unique file name
        $fileName = time() . '.' . $image->getClientOriginalName();

        // Store the image in the public disk under the specified path
        $image->storeAs($path, $fileName, 'public');

        // Return the full public URL of the uploaded image
        return Storage::disk('public')->url($path . '/' . $fileName);
    }

    /**
     * Delete an image from the public storage.
     *
     * @param string $imagePath
     * @return bool
     */
    public function deleteImage(string $imagePath): bool
    {
        // Remove the base URL to get the relative path
        $relativePath = str_replace(Storage::disk('public')->url(''), '', $imagePath);

        if (Storage::disk('public')->exists($relativePath)) {
            return Storage::disk('public')->delete($relativePath);
        }

        return false;
    }
}

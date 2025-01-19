<?php

namespace App\Services\Common\Contracts;

use Illuminate\Http\UploadedFile;

interface ImageServiceInterface
{
    /**
     * Upload an image or array of images to the specified path.
     *
     * @param UploadedFile|array $images
     * @param string $path
     * @return string|array
     */
    public function uploadImage(UploadedFile|array $images, string $path): string | array;

    /**
     * Delete an image from the storage.
     *
     * @param string $imagePath
     * @return bool
     */
    public function deleteImage(string $imagePath): bool;
}

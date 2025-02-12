<?php

namespace App\Http\Controllers\Api\Store;

use App\Http\Controllers\Controller;
use App\Models\Image;
use App\Models\Product;
use App\Services\Common\Contracts\ImageServiceInterface;
use Illuminate\Http\Request;
use Spatie\RouteAttributes\Attributes\Delete;
use Spatie\RouteAttributes\Attributes\Post;
use Spatie\RouteAttributes\Attributes\Prefix;

#[Prefix('api/product-images')]
class ProductImageController extends Controller
{
    public function __construct(
        private readonly ImageServiceInterface $imageService,
    ) {}
    #[Delete('/{id}')]
    public function deleteOne(Request $request, int $id)
    {
        $image = Image::where('id', $id)->delete();
        return $this->success(message: "Image Deleted Successfuly");
    }

    #[Delete('/batch')]
    public function deleteBatch(Request $request)
    {
        $validated = $request->validate([
            'images' => 'required|array',
            'images.*' => 'integer|exists:images,id'
        ]);

        Image::destroy($validated['images']);

        return $this->success(message: "Batch images deleted successfully");
    }


    #[Post()]
    public function addImageToProduct(Request $request)
    {
        $validatedData = $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg',
            'product_id' => 'required|exists:products,id'
        ]);
        $savedImage = $this->imageService->uploadImage($validatedData['image'], '/products');

        $image = Image::create(["image" => $savedImage, "product_id" => $validatedData['product_id']]);
        return $this->success(["image" => $image], statusCode: 201);
    }

    #[Post('/batch')]
    public function addBatchImages(Request $request)
    {
        $validatedData = $request->validate([
            'product_id' => 'required|exists:products,id',
            'images' => 'required|array|min:1',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $savedImages = $this->imageService->uploadImage($validatedData['images'], '/products');

        $data = array_map(function (string $image) use ($validatedData) {
            return [
                "image" => $image,
                "product_id" => $validatedData['product_id'],
            ];
        }, $savedImages);

        $images = Image::insert($data);

        return $this->success(['images' => $images], statusCode: 201);
    }
}

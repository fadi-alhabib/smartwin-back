<?php

namespace App\Http\Controllers\Api\Store;

use App\DTOs\Store\CreateProductDto;
use App\DTOs\Store\UpdateProductDto;
use App\Http\Controllers\Controller;
use App\Http\Requests\Store\CreateProductRequest;
use App\Http\Requests\Store\UpdateProductRequest;
use App\Http\Resources\Store\ProductResource;
use App\Models\Product;
use App\Services\Store\Contracts\ProductServiceInterface;
use Illuminate\Http\Request;
use Spatie\RouteAttributes\Attributes\Delete;
use Spatie\RouteAttributes\Attributes\Get;
use Spatie\RouteAttributes\Attributes\Patch;
use Spatie\RouteAttributes\Attributes\Post;
use Spatie\RouteAttributes\Attributes\Prefix;
use Spatie\RouteAttributes\Attributes\Put;

#[Prefix('api/products')]
class ProductController extends Controller
{
    public function __construct(private readonly ProductServiceInterface $productService) {}

    #[Get('/')]
    public function index()
    {
        $products = $this->productService->all();
        return $this->success(data: ProductResource::collection($products));
    }

    #[Post('/')]
    public function store(CreateProductRequest $request)
    {
        $dto = CreateProductDto::fromRequest($request);
        $dto->set('store_id', $request->user()->store()->id);
        $product = $this->productService->createWithImages($dto);
        return $this->success(data: new ProductResource($product), message: 'Product added successfuly', statusCode: 201);
    }

    #[Get('/{product}')]
    public function show(Product $product)
    {
        return $this->success(data: new ProductResource($product));
    }

    #[Patch('/{product}')]
    public function update(UpdateProductRequest $request, Product $product)
    {
        $dto = UpdateProductDto::fromRequest($request);
        $this->productService->update($product, $dto);
        return $this->success();
    }

    #[Delete('/{product}')]
    public function destroy(Request $request, Product $product)
    {
        $this->productService->delete($product);
        return $this->success();
    }
}

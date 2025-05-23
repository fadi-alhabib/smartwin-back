<?php

namespace App\Http\Controllers\Api\Store;

use App\DTOs\Store\CreateProductDto;
use App\DTOs\Store\UpdateProductDto;
use App\Http\Controllers\Controller;
use App\Http\Requests\Store\CreateProductRequest;
use App\Http\Requests\Store\UpdateProductRequest;
use App\Http\Resources\Store\ProductResource;
use App\Models\Product;
use App\Models\Store;
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
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 20);
        $page = $request->get('page', 1);
        $country = $request->get('country');

        $products = Product::query()
            ->whereHas('images')
            ->whereHas('store', function ($query) use ($country) {
                $query->where('is_active', true);
                if ($country) {
                    $query->where('country', $country);
                }
            });

        $paginatedProducts = $products->paginate($perPage, ['*'], 'page', $page);

        return $this->success(data: ProductResource::collection($paginatedProducts));
    }


    #[Post('/', middleware: "auth:sanctum")]
    public function store(CreateProductRequest $request)
    {
        $dto = CreateProductDto::fromRequest($request);
        $userId = $request->user()->id;
        $store = Store::where("user_id", $userId)->first();
        $dto->set('store_id', $store->id);
        $product = $this->productService->createWithImages($dto);
        return $this->success(data: new ProductResource($product), message: 'Product added successfuly', statusCode: 201);
    }

    #[Get('/{product}', middleware: "auth:sanctum")]
    public function show(Product $product)
    {
        return $this->success(data: new ProductResource($product));
    }

    #[Post('/{product}', middleware: "auth:sanctum")]
    public function update(UpdateProductRequest $request, Product $product)
    {
        $dto = UpdateProductDto::fromRequest($request);
        $this->productService->update($product, $dto);
        return $this->success();
    }

    #[Delete('/{product}', middleware: "auth:sanctum")]
    public function destroy(Request $request, Product $product)
    {
        $this->productService->delete($product);
        return $this->success();
    }
}

<?php

namespace App\Http\Controllers\Api\Store;

use App\DTOs\Store\CreateStoreDto;
use App\DTOs\Store\UpdateStoreDto;
use App\Http\Controllers\Controller;
use App\Http\Requests\Store\CreateStoreRequest;
use App\Http\Requests\Store\UpdateStoreRequest;
use App\Http\Resources\Store\ShowStoreRequest;
use App\Http\Resources\Store\StoreResource;
use App\Models\Store;
use App\Services\Common\Contracts\ImageServiceInterface;
use App\Services\Store\Contracts\StoreServiceInterface;
use Illuminate\Http\Request;
use Spatie\RouteAttributes\Attributes\Delete;
use Spatie\RouteAttributes\Attributes\Get;
use Spatie\RouteAttributes\Attributes\Patch;
use Spatie\RouteAttributes\Attributes\Post;
use Spatie\RouteAttributes\Attributes\Prefix;
use Spatie\RouteAttributes\Attributes\Put;

#[Prefix('api/stores')]
class StoreController extends Controller
{
    public function __construct(
        private readonly StoreServiceInterface $storeService,
        private readonly ImageServiceInterface $imageService,
    ) {}


    #[Get('/')]
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 20);  // default to 20 if not provided
        $page = $request->get('page', 1);  // default to the first page

        $stores = Store::where('is_active', true)->whereHas('products', function ($query) {
            $query->whereHas('images');
        })->paginate($perPage, ['*'], 'page', $page);

        return $this->success(data: StoreResource::collection($stores));
    }

    #[Get('/me', middleware: 'auth:sanctum')]
    public function getMyStore(Request $request)
    {

        try {
            $userId = $request->user()->id;
            $store = $this->storeService->getMyStore($userId);
            return $this->success(data: new StoreResource($store));
        } catch (\Throwable $th) {
            return $this->success($th, statusCode: 404);
        }
    }
    #[Post('/', middleware: "auth:sanctum")]
    public function store(CreateStoreRequest $request)
    {
        $dto = CreateStoreDto::fromRequest($request);
        $dto->set('user_id',  $request->user()->id);
        $store = $this->storeService->createWithImage($dto);
        return $this->success(data: new StoreResource($store));
    }

    #[Post('/{store}')]
    public function update(UpdateStoreRequest $request, Store $store)
    {
        $validatedData = $request->validated();
        $dto = UpdateStoreDto::fromArray($validatedData);
        if ($request->file('image') && $request->file('image') !== null) {
            $image = $this->imageService->uploadImage($request->file('image'), '/store');
            $dto->set('image', $image);
        }
        $this->storeService->update($store, $dto);
        return $this->success();
    }

    #[Get('/{store}')]
    public function show(Store $store)
    {
        return $this->success(data: new ShowStoreRequest($store));
    }


    #[Delete('/{store}')]
    public function destroy(Request $request, Store $store)
    {
        $this->storeService->delete($store);
        return $this->success();
    }
}

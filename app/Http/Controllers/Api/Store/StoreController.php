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
use App\Services\Store\Contracts\StoreServiceInterface;
use Illuminate\Http\Request;
use Spatie\RouteAttributes\Attributes\Delete;
use Spatie\RouteAttributes\Attributes\Get;
use Spatie\RouteAttributes\Attributes\Patch;
use Spatie\RouteAttributes\Attributes\Post;
use Spatie\RouteAttributes\Attributes\Prefix;

#[Prefix('api/stores')]
class StoreController extends Controller
{
    public function __construct(private readonly StoreServiceInterface $storeService) {}

    #[Get('/')]
    public function index()
    {
        $stores = $this->storeService->all();
        return $this->success(data: StoreResource::collection($stores));
    }
    #[Get('me', middleware: 'auth:sanctum')]
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
    #[Post('/')]
    public function store(CreateStoreRequest $request)
    {
        $dto = CreateStoreDto::fromRequest($request);
        $dto->set('user_id', $request->user()->id);
        $store = $this->storeService->createWithImage($dto);
        return $this->success(data: new StoreResource($store));
    }

    #[Get('/{store}')]
    public function show(Store $store)
    {
        return $this->success(data: new ShowStoreRequest($store));
    }


    #[Patch('/{store}')]
    public function update(UpdateStoreRequest $request, Store $store)
    {
        $dto = UpdateStoreDto::fromRequest($request);
        $this->storeService->update($store, $dto);
        return $this->success();
    }
    #[Delete('/{store}')]
    public function destroy(Request $request, Store $store)
    {
        $this->storeService->delete($store);
        return $this->success();
    }
}

<?php

namespace App\Http\Resources\Store;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'price' => $this->price,
            'store' => new ProductStoreResource($this->store),
            'images' => ProductImageResource::collection($this->images),
            'average_rating' => $this->averageRating(),
            'user_has_rated' => $this->userHasRated(),
            'your_rating' => $this->userRating(),
        ];
    }
}

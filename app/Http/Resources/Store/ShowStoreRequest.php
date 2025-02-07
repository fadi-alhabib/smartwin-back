<?php

namespace App\Http\Resources\Store;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ShowStoreRequest extends JsonResource
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
            'type' => $this->type,
            'country' => $this->country,
            'address' => $this->address,
            'phone' => $this->phone,
            'image' => $this->image,
            'points' => $this->points,
            'products' => ProductResource::collection($this->products),
        ];
    }
}

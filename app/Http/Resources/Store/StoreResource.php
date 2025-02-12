<?php

namespace App\Http\Resources\Store;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StoreResource extends JsonResource
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
            'owner' => $this->user->full_name,
            'image' => $this->image,
            'products' => $this->products,
        ];
    }
}

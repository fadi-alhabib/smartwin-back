<?php

namespace App\Http\Resources\Room\Messages;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MessageResource extends JsonResource
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
            'message' => $this->message,
            'sender_name' => $this->user->full_name,
            'sender_id' => $this->user->id,
            'sent_at' => $this->created_at,
        ];
    }
}

<?php

namespace App\Http\Resources\Room\Quiz;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ImageQuestionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return  [
            'id' => $this->id,
            'title' => $this->title,
            'image' => $this->image,
            'answers' => AnswerResource::collection($this->answers),
        ];
    }
}

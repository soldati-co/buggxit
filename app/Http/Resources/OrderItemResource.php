<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderItemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'dress_id' => $this->dress_id,
            'quantity' => $this->quantity,
            'price' => $this->price,
            'total' => $this->price * $this->quantity,
            'dress' => new DressResource($this->whenLoaded('dress')),
        ];
    }
}

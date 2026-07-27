<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
            'parent_id' => $this->parent_id,
            'is_active' => (bool) $this->is_active,
            'sort_order' => $this->sort_order,
            'dresses_count' => $this->when($this->relationLoaded('dresses') || isset($this->dresses_count), $this->dresses_count ?? null),
        ];
    }
}

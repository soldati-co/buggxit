<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class DressResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'slug' => $this->slug,
            'name' => $this->name,
            'sku' => $this->sku,
            'description' => $this->description,
            'price' => $this->price,
            'compare_at_price' => $this->compare_at_price,
            'stock_quantity' => $this->stock_quantity,
            'low_stock_threshold' => $this->low_stock_threshold,
            'turnaround_time' => $this->turnaround_time,
            'expected_delivery' => $this->expected_delivery,
            'status' => $this->status,
            'is_featured' => $this->is_featured,
            'is_taxable' => $this->is_taxable,
            'requires_shipping' => $this->requires_shipping,
            'sizes' => $this->sizes,
            'colors' => $this->colors,
            'meta_title' => $this->meta_title,
            'meta_description' => $this->meta_description,
            'main_image_url' => $this->main_image_url,
            'gallery_image_urls' => $this->gallery_image_urls,
            'card_image_url' => $this->card_image_url,
            'has_card_crop' => $this->has_card_crop,
            'card_image_new_arrivals_url' => $this->card_image_new_arrivals_url,
            'has_card_crop_new_arrivals' => $this->has_card_crop_new_arrivals,
            'categories' => CategoryResource::collection($this->whenLoaded('categories')),
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}

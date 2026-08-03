<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Schema;

class HeroSlideResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'         => $this->id,
            'title'      => $this->title,
            'headline'   => $this->headline,
            'subheading' => $this->subheading,
            'cta_text'   => $this->cta_text,
            'cta_url'    => $this->cta_url,
            'image_url'  => Schema::hasTable('images') ? $this->image?->url : null,
            'alt_text'   => $this->alt_text ?? $this->headline ?? $this->title ?? 'Hero slide',
        ];
    }
}
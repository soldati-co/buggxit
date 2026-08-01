<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

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
            'image_url'  => $this->image?->url,       // uses the accessor defined on the Image model
            'alt_text'   => $this->alt_text ?? $this->headline ?? $this->title ?? 'Hero slide',
        ];
    }
}
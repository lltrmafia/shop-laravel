<?php

namespace App\Http\Resources\Api\Catalog;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CatalogProductResource extends JsonResource
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
            'title' => $this->title,
            'description' => $this->description,
            'price' => (float) $this->price,
            'old_price' => (float) $this->old_price,
            'slug' => $this->slug,
            'stock_status' => $this->stock_status,
            'image_preview' => $this->image_preview,
        ];
    }
}

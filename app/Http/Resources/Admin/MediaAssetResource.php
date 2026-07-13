<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MediaAssetResource extends JsonResource
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
            'disk' => $this->disk,
            'path' => $this->path,
            'image_url' => $this->image_url,
            'filename' => $this->filename,
            'mime_type' => $this->mime_type,
            'size_human' => round($this->size / 1024, 2) . ' KB',
            'width' => $this->width,
            'height' => $this->height,
            'alt' => $this->alt,
            'pivot_products_count' => $this->pivot_products_count,
            'created_by' => $this->created_by,
        ];
    }
}

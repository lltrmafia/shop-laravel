<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResourceWithParentsNames extends JsonResource
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
            'content' => $this->content,
            'price' => $this->price,
            'old_price' => $this->old_price,
            'qty' => $this->qty,
            'product_group_id' => $this->product_group_id,
            'category_id' => $this->category_id,
            'slug' => $this->slug,
            'article' => $this->article,
            'category_name' => $this->category_name,
            'product_group_name' => $this->product_group_name,
            'parent_id' => $this->parent_id,
            'image_preview' => $this->image_preview,
            'product_gallery' => $this->product_gallery,
            'children_count' => $this->children_count,
        ];
    }
}

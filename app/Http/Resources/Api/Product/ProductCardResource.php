<?php

namespace App\Http\Resources\Api\Product;

use App\Services\Client\ProductService;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductCardResource extends JsonResource
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
            'price' => (float) $this->price,
            'old_price' => (float) $this->old_price,
            'qty' => $this->qty,
            'product_group_id' => $this->product_group_id,
            'category_id' => $this->category_id,
            'slug' => $this->slug,
            'article' => $this->article,
            'category_name' => $this->category_name,
            'product_group_name' => $this->product_group_name,
            'parent_id' => $this->parent_id,
            'product_media' => $this->product_media,
            'stock_status' => $this->stock_status,
            'params' => $this->params,
            'compare_params' => ProductService::getCompareParams($this, $this->params),
            'children' => ProductCardResource::collection(
                $this->whenLoaded('children')
            ),
        ];
    }
}

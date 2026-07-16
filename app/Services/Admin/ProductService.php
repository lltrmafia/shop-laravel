<?php

namespace App\Services\Admin;

use App\Models\Product;
use Illuminate\Support\Facades\DB;

class ProductService
{
    public static function store(array $data): Product
    {

        $product = Product::create($data['product']);
        ProductService::syncMedia($product, $data['media']);
        ProductService::syncParams($product, $data);

        return $product;
    }

    public static function update(Product $product, array $data): Product
    {
        $product->update($data['product']);
        ProductService::syncMedia($product, $data['media']);
        ProductService::syncParams($product, $data);
        return $product->fresh();
    }

    public static function restore($restoredProduct): Product
    {

        $restoredProduct->restore();
        return $restoredProduct;
    }

    public static function forceDelete($id): void
    {
        $product = Product::withTrashed()->findOrFail($id);
        $ids = $product->trashedChildren()->pluck('id')->push($product->id)->all();
        self::forceDeleteMass($ids);
    }

    public static function syncMedia($product, array $media = []): void
    {
        $mediaItems = [];
        $mediaPreview = $media['mediaAssetPreview'] ?? [];
        $mediaGallery = $media['mediaAssetGallery'] ?? [];
        if (!empty($mediaGallery['selected_images_ids'])) {
            foreach ($mediaGallery['selected_images_ids'] as $id) {
                $mediaItems[$id] = [
                    'collection' => $mediaGallery['current_media_mode'],
                    'sort_order' => $mediaGallery['sort_order'],
                ];
            }
        }
        if (!empty($mediaPreview['selected_image_id'])) {
            $previewId = $mediaPreview['selected_image_id'];
            $mediaItems[$previewId] = [
                'collection' => $mediaPreview['current_media_mode'],
                'sort_order' => $mediaPreview['sort_order'],
            ];
        }
        !empty($mediaItems) ? $product->media()->sync($mediaItems) : $product->media()->sync([]);
    }

    public static function syncParams($product, array $data = []): void
    {
        $params = [];
        if (!empty($data['params'])) {
            foreach ($data['params'] as $param) {
                $params[$param['id']] = [
                    'value' => $param['value'],
                    'display_name' => $param['display_name'] ?? null,
                ];
            }

        }
        !empty($params) ? $product->params()->sync($params) : $product->params()->sync([]);
    }

    private static function forceDeleteMass(array $ids): void
    {
        DB::transaction(function () use ($ids) {
            $products = Product::withTrashed()->whereIn('id', $ids)->get();
            $products->each(function ($product) {
                ProductService::syncMedia($product);
                ProductService::syncParams($product);
                $product->forceDelete();
            });
        });
    }


}

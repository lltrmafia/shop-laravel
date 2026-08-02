<?php

namespace App\Services\Client;


use App\Models\Product;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use PhpParser\Node\Expr\Cast\Object_;

class ProductService
{

    public static function getProductQty($products): array
    {
        $goods = match (true) {
            $products->count() <= 1 => 'товар',
            $products->count() <= 4 && $products->count() > 1 => 'товара',
            default => 'товаров'
        };
        return [
            'count' => $products->count(),
            'label' => $goods,
        ];
    }

    public static function getVariableParams($product): array
    {
        $childrenParams = $product->children->flatMap(function ($child) {
            return $child->params;
        });
        return $childrenParams->groupBy('id')->filter(function ($param) {
            return $param->first()->is_variant;
        })->map(function ($param) {
            $first = $param->first();
            return [
                'id' => $first->id,
                'title' => $first->title,
                'input_type' => $first->input_type,
                'values' => $param->map(function ($value) {
                    return [
                        'value' => $value->pivot->value,
                        'display_name' => $value->pivot->display_name,
                    ];
                })->unique()->values()->all(),
            ];
        })->values()->all();
    }

    public static function getCompareParams($product, $params): object
    {
        if (!$product->parent_id) return (object)[];

        $result = $params->filter(function ($param) {
            return $param->is_variant;
        })->mapWithKeys(function ($item) {
            return [$item->id => $item->pivot->value];
        });

        return (object)$result->all();
    }

    public static function getParentForSliderResults($query, $request): object
    {
        $childIds = [];

        if($request->query('filter')){
            $childIds = $query->get()->map(function ($item) {
                return [
                    'id' => $item->id,
                    'parent_id' => $item->parent_id,
                ];
            })->unique('parent_id')->values()->all();

            $parentIds = $query->get()->pluck('parent_id')->unique()->values();
            $query = Product::query()->whereIn('id', $parentIds);
        }
        return (object) [
            'query' => $query,
            'childIds' => $childIds,
        ];
    }

    public static function attachSelectedChildId($products, $childIds): LengthAwarePaginator
    {
        $childIds = collect($childIds)->keyBy('parent_id');
        foreach ($products as $product) {
            $product->selected_child_id = $childIds[$product->id]['id'] ?? null;
        }
        return $products;
    }
}

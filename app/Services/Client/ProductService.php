<?php

namespace App\Services\Client;


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

        return (object) $result->all();
    }
}

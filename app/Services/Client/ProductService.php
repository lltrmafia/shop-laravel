<?php

namespace App\Services\Client;

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

}

<?php

namespace App\Services\Admin;

use App\Models\ProductGroup;

class ProductGroupService
{
    public static function store(array $data): ProductGroup
    {
        $productGroup = ProductGroup::create($data);
        return $productGroup;
    }
    public static function update(ProductGroup $productGroup, array $data): ProductGroup
    {
        $productGroup->update($data);
        return $productGroup->fresh();
    }
}

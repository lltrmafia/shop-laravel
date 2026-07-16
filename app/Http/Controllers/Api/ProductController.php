<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\Product\ProductCardResource;
use App\Models\Category;
use App\Models\Product;
use App\Services\Client\CategoryService;
use App\Services\Client\ProductService;

class ProductController extends Controller
{
//    /**
//     * Display a listing of the resource.
//     */
//    public function index()
//    {
//        $products = Product::with(['category', 'productGroup', 'media'])->paginate(12);
//        return ProductCardResource::collection($products);
//    }


    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        $category = Category::find($product->category_id);
        return [
            'product' => ProductCardResource::make(Product::with(['params', 'children.params'])->findOrFail($product->id)),
            'breadcrumbs' => CategoryService::getBreadcrumbs($category),
            'variableParams' => ProductService::getVariableParams($product),
        ];
    }
}

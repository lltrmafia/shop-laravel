<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\Catalog\CatalogProductResource;
use App\Models\Category;
use App\Models\Product;
use App\Services\Client\ProductFilterBuilder;
use App\Services\Client\ProductService;
use Illuminate\Http\Request;

class CatalogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Product::with(['category', 'productGroup', 'media']);
        $data = ProductFilterBuilder::applyFilters($query, $request);
        $query = $data->query;
        $childIds = $data->childIds;
        $query = ProductFilterBuilder::applySort($query, $request);
        $products = (clone $query)->whereNull('parent_id')->with('children')->paginate(12);
        ProductService::attachSelectedChildId($products, $childIds);
        return [
            'title' => 'Каталог',
            'products' => CatalogProductResource::collection($products),
            'productsQty' => ProductService::getProductQty((clone $query)->get()),
            'params' => ProductFilterBuilder::getParams((clone $query)->whereNotNull('parent_id')->get()),
            'breadcrumbs' => []
        ];

    }


}

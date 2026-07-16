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
        $query = Product::with(['category', 'productGroup', 'media'])->whereNull('parent_id');
        $query = ProductFilterBuilder::applyFilters($query, $request);
        $query = ProductFilterBuilder::applySort($query, $request);
        return [
            'title' => 'Каталог',
            'products' => CatalogProductResource::collection($query->paginate(12)),
            'productsQty' => ProductService::getProductQty($query->get()),
            'params' => ProductFilterBuilder::getParams($query->get()),
            'breadcrumbs' => []
        ];

    }


}

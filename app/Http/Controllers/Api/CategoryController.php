<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\Catalog\CatalogProductResource;
use App\Http\Resources\Api\Categories\CategoryResource;
use App\Models\Category;
use App\Models\Product;
use App\Services\Client\CategoryService;
use App\Services\Client\ProductFilterBuilder;
use App\Services\Client\ProductService;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::all();
        return CategoryResource::collection($categories);
    }

    public function show(Category $category, Request $request)
    {
        $ids = $this->getChildren($category);
        $query = Product::whereIn('category_id', $ids);
        $query = ProductFilterBuilder::applyFilters($query, $request);
        $query = ProductFilterBuilder::applySort($query, $request);
        return [
            'title' => $category->title,
            'products' => CatalogProductResource::collection($query->get()),
            'breadcrumbs' => CategoryService::getBreadcrumbs($category),
            'productsQty' => ProductService::getProductQty($query->get()),
            'params' => ProductFilterBuilder::getParams($query->get())
        ];
    }

    private function getChildren($category)
    {
        $arr = [$category->id];
        $category->children->each(function ($child) use (&$arr) {
            $arr = array_merge($arr, $this->getChildren($child));
        });
        return array_values($arr);
    }

}

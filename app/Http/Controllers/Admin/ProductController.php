<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Product\StoreRequest;
use App\Http\Requests\Admin\Product\UpdateRequest;
use App\Http\Resources\Admin\CategoryResource;
use App\Http\Resources\Admin\MediaAssetResource;
use App\Http\Resources\Admin\ParamProductResource;
use App\Http\Resources\Admin\ParamResource;
use App\Http\Resources\Admin\ProductGroupResource;
use App\Http\Resources\Admin\ProductResource;
use App\Http\Resources\Admin\ProductResourceWithParentsNames;
use App\Models\Category;
use App\Models\Param;
use App\Models\Product;
use App\Models\ProductGroup;
use App\Services\Admin\ProductService;
use Illuminate\Http\Response;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = ProductResourceWithParentsNames::collection(Product::with(['category', 'productGroup', 'media'])
            ->whereNull('parent_id')->withCount('children')->get());
        $trashedProducts = ProductResourceWithParentsNames::collection(Product::onlyTrashed()->with(['category', 'productGroup', 'media'])
            ->whereNull('parent_id')->withCount('children')->get());
        return inertia('Admin/Product/Index', compact('products', 'trashedProducts'));
    }

    public function indexChildren(Product $product)
    {
        return ProductResourceWithParentsNames::collection($product->children()->with(['category', 'productGroup', 'media'])->get());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = CategoryResource::collection(Category::all());
        $productGroups = ProductGroupResource::collection(ProductGroup::all());
        $params = ParamResource::collection(Param::all());
        return inertia('Admin/Product/Create', compact('categories', 'productGroups',  'params', 'params'));
    }

    public function createChild(Product $product)
    {
        $categories = CategoryResource::collection(Category::all());
        $productGroups = ProductGroupResource::collection(ProductGroup::all());
        $preview = $product->media()->wherePivot('collection', 'preview')->first();
        $productPreview = $preview ? MediaAssetResource::make($preview)->resolve() : null;
        $gallery = $product->media()->wherePivot('collection', 'gallery')->get();
        $productGallery = $gallery ? MediaAssetResource::collection($gallery)->resolve() : null;
        $parameters = $product->params()->get();
        $paramsProduct = $parameters ? ParamProductResource::collection($parameters)->resolve() : [];
        $product = ProductResource::make($product)->resolve();
        $params = ParamResource::collection(Param::all());
        return inertia('Admin/Product/CreateChild', compact('product',  'categories', 'productGroups', 'productPreview',  'productGallery', 'productGallery',  'params', 'paramsProduct'));
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        $data = $request->validated();
        $product = ProductService::store($data);
        return ProductResource::make($product)->resolve();
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        $product = ProductResourceWithParentsNames::make($product);
        return inertia('Admin/Product/Show', compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        $productChildren = ProductResourceWithParentsNames::collection($product->children()->with(['category', 'productGroup', 'media'])->get())->resolve();
        $productChildrenTrashed = ProductResourceWithParentsNames::collection($product->trashedChildren()->with(['category', 'productGroup', 'media'])->get())->resolve();
        $categories = CategoryResource::collection(Category::all());
        $productGroups = ProductGroupResource::collection(ProductGroup::all());
        $preview = $product->media()->wherePivot('collection', 'preview')->first();
        $productPreview = $preview ? MediaAssetResource::make($preview)->resolve() : null;
        $gallery = $product->media()->wherePivot('collection', 'gallery')->get();
        $productGallery = $gallery ? MediaAssetResource::collection($gallery)->resolve() : null;
        $parameters = $product->params()->get();
        $paramsProduct = $parameters ? ParamProductResource::collection($parameters)->resolve() : [];
        $product = ProductResource::make($product)->resolve();
        $params = ParamResource::collection(Param::all());
        return inertia('Admin/Product/Edit',
            compact('product',  'categories', 'productGroups',
                'productPreview', 'productGallery',  'params', 'paramsProduct', 'productChildren',  'productChildrenTrashed'));
    }

    public function editChild(Product $product, Product $productChild)
    {
        $categories = CategoryResource::collection(Category::all());
        $productGroups = ProductGroupResource::collection(ProductGroup::all());
        $preview = $productChild->media()->wherePivot('collection', 'preview')->first();
        $productPreview = $preview ? MediaAssetResource::make($preview)->resolve() : null;
        $gallery = $productChild->media()->wherePivot('collection', 'gallery')->get();
        $productGallery = $gallery ? MediaAssetResource::collection($gallery)->resolve() : null;
        $parameters = $productChild->params()->get();
        $paramsProduct = $parameters ? ParamProductResource::collection($parameters)->resolve() : [];
        $parentProduct = ProductResourceWithParentsNames::make($product)->resolve();
        $productChild = ProductResource::make($productChild)->resolve();
        $params = ParamResource::collection(Param::all());
        return inertia('Admin/Product/EditChild', compact('parentProduct', 'productChild',  'categories', 'productGroups', 'productPreview', 'productGallery',  'params', 'paramsProduct'));
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, Product $product)
    {
        $data = $request->validated();
        $product = ProductService::update($product, $data);
        return ProductResource::make($product)->resolve();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        if(count($product->children)) $product->children()->delete();
        $product->delete();
        return response()->json([
            'message' => 'success'
        ], Response::HTTP_OK);
    }
    public function trash()
    {
        $products = ProductResourceWithParentsNames::collection(Product::with(['category', 'productGroup', 'media'])
            ->whereNull('parent_id')->withCount('children')->get());
        $trashedProducts = ProductResourceWithParentsNames::collection(Product::onlyTrashed()->with(['category', 'productGroup', 'media'])
            ->whereNull('parent_id')->withCount('children')->get());
        return inertia('Admin/Product/Trash', compact('trashedProducts', 'products'));
    }
    public function restore($id)
    {
        $restoredProduct = Product::withTrashed()->findOrFail($id);
        ProductService::restore($restoredProduct);
        return $restoredProduct;
    }
    public function ForceDelete($id)
    {
        ProductService::forceDelete($id);
        return response()->json([
            'message' => 'success'
        ], Response::HTTP_OK);
    }
}

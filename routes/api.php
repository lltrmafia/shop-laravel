<?php

use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\CatalogController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
//});

Route::get('/auth/check', function () {
    return response()->json([
        'authenticated' => auth()->check(),
    ]);
});

Route::get('/products/{product}', [ProductController::class, 'show']);
Route::get('catalog', [CatalogController::class, 'index']);
Route::get('categories', [CategoryController::class, 'index']);
Route::get('categories/{category}', [CategoryController::class, 'show']);

Route::prefix('cart')->controller(CartController::class)->group(function () {
    Route::get('/', 'index');
    Route::post('/', 'store');
    Route::get('/count', 'count');
    Route::patch('/{cartItem}', 'update');
    Route::delete('/{cartItem}', 'destroy');
});

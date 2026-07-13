<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\MediaAssetController;
use App\Http\Controllers\Admin\ParamController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ProductGroupController;
use App\Http\Middleware\IsAdminMiddleware;
use Illuminate\Support\Facades\Route;

Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard')->prefix('admin')->middleware('auth', IsAdminMiddleware::class);
Route::prefix('admin')->name('admin.')->middleware(['auth', IsAdminMiddleware::class])->group(function () {
    Route::get('products/trash', [ProductController::class, 'trash'])->name('products.trash');
    Route::get('products/{product}/children', [ProductController::class, 'indexChildren'])->name('products.children');
    Route::get('products/{product}/children/create', [ProductController::class, 'createChild'])->name('products.children.create');
    Route::get('products/{product}/children/{productChild}/edit', [ProductController::class, 'editChild'])->name('products.children.edit');
    Route::delete('products/{product}/force-delete', [ProductController::class, 'ForceDelete'])->name('products.force-delete');
    Route::post('products/{product}/restore', [ProductController::class, 'restore'])->name('products.restore');
    Route::resource('products', ProductController::class);
    Route::get('categories/trash', [CategoryController::class, 'trash'])->name('categories.trash');
    Route::delete('categories/{category}/force-delete', [CategoryController::class, 'ForceDelete'])->name('categories.force-delete');
    Route::post('categories/{category}/restore', [CategoryController::class, 'restore'])->name('categories.restore');
    Route::resource('categories', CategoryController::class);
    Route::get('params/trash', [ParamController::class, 'trash'])->name('params.trash');
    Route::delete('params/{param}/force-delete', [ParamController::class, 'forceDelete'])->name('params.force-delete');
    Route::post('params/{param}/restore', [ParamController::class, 'restore'])->name('params.restore');
    Route::resource('params', ParamController::class);
    Route::resource('product-groups', ProductGroupController::class)->parameters(['product-groups' => 'productGroup']);
    Route::get('/media', [MediaAssetController::class, 'index'])->name('media.index');
    Route::get('/media/list', [MediaAssetController::class, 'list'])->name('media.list');
    Route::post('/media', [MediaAssetController::class, 'store'])->name('media.store');
    Route::patch('/media/{mediaAsset}', [MediaAssetController::class, 'update'])->name('media.update');
    Route::delete('/media/{mediaAsset}', [MediaAssetController::class, 'destroy'])->name('media.destroy');
});

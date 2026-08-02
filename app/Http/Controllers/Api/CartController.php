<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Client\Cart\StoreCartRequest;
use App\Http\Resources\Api\Cart\CartResource;
use App\Models\Cart;
use App\Services\Client\CartService;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index(Request $request)
    {
        return [
            'items' => CartResource::make(CartService::getCartItems($request)),
        ];
    }

    public function update(Request $request, $productId)
    {
        $cartItem = CartService::getCartItems($request)->where('product_id', $productId)->first();
        $cartItem->increment('qty', $request->delta);
        $cartItem->save();
    }
    public function store(StoreCartRequest $request)
    {
            $cart = CartService::addProduct($request->cookie('cart_token'), $request->productEntries);
            $response = CartResource::make($cart)->response();

            if (!$request->cookie('cart_token')) {
                $response->cookie(
                    'cart_token',
                    $cart->token,
                    60 * 24 * 30
                );
            }
            return $response;
    }

    public function destroy(Request $request, $productId)
    {
        $cartItem = CartService::getCartItems($request)->where('product_id', $productId)->first();
        $cartItem->delete();
    }

    public function count(Request $request)
    {
        return response()->json([
            'count' => CartService::findCurrentCart($request->cookie('cart_token'))
        ]);

    }


}

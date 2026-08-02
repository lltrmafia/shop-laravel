<?php

namespace App\Services\Client;

use App\Models\Cart;
use App\Models\CartItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CartService
{
    public static function getOrCreateGuestCart(?string $cartToken = null): Cart
    {

        if ($cartToken) {
            $cart = Cart::where('token', $cartToken)->first();
            if(!$cart) $cart = Cart::create(['token' => $cartToken]);
        } else {
            $token = fake()->uuid();
            $cart = Cart::create(['token' => $token]);
        }
        return $cart;

    }

    public static function getOrCreateUserCart($user, $token): Cart
    {
       return DB::transaction(function () use ($user, $token) {
            $cart = Cart::where('user_id', $user->id)->first();
            if(!$cart) $cart = Cart::create(['user_id' => $user->id]);
            $oldCart = Cart::where('token', $token)->first();
            if($oldCart) {
                CartItem::whereIn('cart_id', [$oldCart->id, $cart->id])->get()
                    ->groupBy('product_id')->filter(function ($item) {
                        return $item->count() > 1;
                    })->each(function ($item) use ($cart, $oldCart) {
                        $userItem = $item->first(function ($item) use ($cart) {
                            return $item->cart_id == $cart->id;
                        });
                        $guestItem = $item->first(function ($item) use ($oldCart) {
                            return $item->cart_id == $oldCart->id;
                        });
                        $userItem->qty += $guestItem->qty;
                        $userItem->save();
                        $guestItem->delete();
                    });
                $oldCart->items()->update(['cart_id' => $cart->id]);
                $oldCart->delete();
            }
            return $cart;
        });
    }

    public static function getUserCart($user): Cart
    {
        $cart = Cart::where('user_id', $user->id)->first();
        if(!$cart) $cart = Cart::create(['user_id' => $user->id]);
        return $cart;
    }

    public static function addProduct($token, $data): Cart
    {
        $cart = Auth::check() ? self::getUserCart(auth()->user()) : self::getOrCreateGuestCart($token);
        self::getOrStoreCartItems($cart, $data);
        return $cart;
    }

    public static function getOrStoreCartItems($cart, $data): CartItem
    {
        $cartItem = $cart->items()->where('product_id', $data['product_id'])->first();
        if ($cartItem) {
            $cartItem->qty += $data['qty'];
            $cartItem->save();
        } else {
            $cartItem = $cart->items()->create([
                'product_id' => $data['product_id'],
                'qty' => $data['qty'],
            ]);
        }
        return $cartItem;
    }

    public static function findCurrentCart($token): int
    {
        if(auth()->check()) {
            $count = auth()->user()->cart->items->pluck('qty')->sum();
        }else{
            $cart = $token ? Cart::where('token', $token)->first() : null;
            $count = $cart ? $cart->items->pluck('qty')->sum() : 0;
        }
        return $count;
    }

    public static function getCartItems($request)
    {
        if ($token = $request->cookie('cart_token')) {
            return Cart::where('token', $token)->first()?->items;
        }
        if (auth()->check()) {
            return auth()->user()->cart?->items;
        }

        return null;
    }
}

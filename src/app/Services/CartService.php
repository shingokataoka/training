<?php

namespace App\Services;

class CartService
{

    public static function getItemsInCart($products)
    {
        $items = [];

        $products->load('shop.owner', 'stocks');
        foreach ($products as $product)
        {
            $items[] = [
                // 商品情報（id,名前,価格）を取得
                'id' => $product->id,
                'name' => $product->name,
                'price' => $product->price,
                // オーナー情報（名前,メルアド）を取得
                'ownerName' => $product->shop->owner->name,
                'ownerEmail' => $product->shop->owner->email,
                // 在庫数
                'quantity' => $product->pivot->quantity,
            ];
        }
        dd($items);
        return $items;
    }

}


<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Cart;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function add(Request $request)
    {
        // カートに商品があるか確認
        $itemInCart = Cart::where('user_id', auth::id())
            ->where('product_id', $request->product_id)
            ->first();
        // あれば数量に＋する
        if ($itemInCart) {
            $itemInCart->quantity += $request->quantity;
            $itemInCart->save();
        // なければ新規作成する
        } else {
            Cart::create([
                'user_id' => Auth::id(),
                'product_id' => $request->product_id,
                'quantity' => $request->quantity,
            ]);
        }
dd('てすと');
        // return redirect()->route('user.cart.index');
    }
}

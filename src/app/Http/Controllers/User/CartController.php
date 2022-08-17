<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Cart;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class CartController extends Controller
{

    public function index()
    {
        // カート内の商品を取得
        $user = User::findOrFail(Auth::id());
        $products = $user->products;

        // トータルの金額を取得
        $totalPrice = 0;
        foreach ($products as $product) {
            $totalPrice += $product->price * $product->pivot->quantity;
        }

        return view('user/cart', compact('products', 'totalPrice'));
    }


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

        return redirect()->route('user.cart.index');
    }

}

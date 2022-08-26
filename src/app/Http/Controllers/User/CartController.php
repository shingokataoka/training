<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Cart;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Stock;
use App\Services\CartService;

use Illuminate\Support\Facades\Mail;
use App\Jobs\SendThanksMail;
use App\Mail\ThanksMail;
use App\Jobs\SendOrderedMail;
use App\Mail\OrderedMail;

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

    public function delete($id)
    {
        Cart::where('product_id', $id)
            ->where('user_id', Auth::id())
            ->delete();

        return redirect()->route('user.cart.index');
    }


    public function checkout()
    {
        // カートの商品一覧を取得
        $user = User::findOrFail(Auth::id());
        $products = $user->products;

        // 在庫が不足ならリダイレクト
        foreach ($products as $product) {
            // Stock内の商品数量が足りているか確認
            $quantity = Stock::where('product_id', $product->id)->sum('quantity');
            // 足りていなければ「カート」にリダイレクトでフラッシュメッセージを出す
            if ($product->pivot->quantity > $quantity) {
                return redirect()->route('user.cart.index')
                    ->with([
                        'message' => "申し訳ございません。商品「{$product->name}」の在庫数が足りません。<br>残りの数は {$quantity} 個です。",
                        'status' => 'alert',
                    ]);
            }
        }

        // stripe処理の前にStock内から在庫数を減らす
        foreach ($products as $product) {
            Stock::create([
                'product_id' => $product->id,
                'type' => \Constant::PRODUCT_LIST['reduce'],
                'quantity' => $product->pivot->quantity * -1,
            ]);
        }

        // Stripe処理用の形式でカートの商品情報を配列に入れる
        $lineItems = [];
        foreach ($products as $product) {
            $lineItems[] = [
                'price_data' => [
                    'currency' => 'jpy',
                    'product_data' => [
                        'name' => $product->name,
                    ],
                    'unit_amount' => $product->price
                ],
                'quantity' => $product->pivot->quantity,
            ];
        }
        // dd($lineItems); // t_stockで在庫が減っているか、$lineItemsの中身を確認する

        // 公式ドキュメント「stripe DOCS」の「クイックスタート」を開く
        // ※注 「web」で「php」で説明されてるか確認して読む
        // 「Checkout セッションを作成する」までスライド → ほぼコピペ
        // URLやAPIキーを適当なのに書き換え
        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET_KEY'));
        header('Content-Type: application/json');

        // ただし、ここだけ「支払いの受付」の「Checkout セッションを作成したら、レスポンスで返された URL に顧客をリダイレクトします。」
        // の下の$session = 〜　に差し替えた
        $checkout_session = \Stripe\Checkout\Session::create([
            'line_items' => $lineItems,
            'mode' => 'payment',
            'success_url' => route('user.cart.success'),    // 成功時の移動先
            'cancel_url' => route('user.cart.cancel'),      // キャンセル時の移動先
        ]);

        header("HTTP/1.1 303 See Other");
        header("Location: " . $checkout_session->url);
        exit;
    }


    public function success()
    {
        ///
        // 購入後メール用のカート内商品情報を取得
        $user = User::with('products.shop.owner')->findOrFail(Auth::id());
        $items = CartService::getItemsInCart($user->products);
        // 非同期でユーザーへ購入お礼メールを送信
        SendThanksMail::dispatch($items, $user);
        // 非同期でオーナーへ購入があったメールを送信
        foreach ($items as $item) {
            SendOrderedMail::dispatch($item, $user);
        }
        ///

        // 決済成功時なので、（ユーザーの）カート内を消す
       Cart::where('user_id', Auth::id())->delete();

       return redirect()->route('user.cart.index');
    }

    public function cancel()
    {
        // 決済キャンセルなので、減らしたStockの在庫を＋して戻す
        $user = User::FindOrfail(Auth::id());
        foreach ($user->products as $product) {
            Stock::create([
                'product_id' => $product->id,
                'type' => \Constant::PRODUCT_LIST['cancel'],
                'quantity' => $product->pivot->quantity,
            ]);
        }

        return redirect()->route('user.cart.index');
    }

}

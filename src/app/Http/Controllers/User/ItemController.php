<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Product;
use Illuminate\Support\Facades\DB;

class ItemController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:users');
    }

    public function index()
    {
        // sum(quantity)が1以上のstocksを取得
        // DB::raw(sql文を文字列で直書きできる)がインジェクション攻撃に注意が必要
        $stocks = DB::table('t_stocks')
            ->select(
                'product_id',
                DB::raw('sum(quantity) as quantity'),
            )
            ->groupBy('product_id')
            ->having('quantity', '>=', 1);

        // やっていること
        // productsと$stocks(quantity >= 1)をinner join（quantityが0以下は消える）
        // このproducts + $stocks とshopsをinner join
        // whereでshopsとproductsのis_selling=trueのみにする
        // [結果]quantity>=1、shopsとproductsのis_seliing=true のProductsを取得できる

        $products = Product::
        // productsにstocks($stocksに付けた別名)をinner joinしてる
        // quantityが1以上 以外のレコードは消える
            joinSub($stocks, 'stocks', function($join) {
                $join->on('products.id', '=', 'stocks.product_id');
            })
            // products + stocksに shopsをinner join
            ->join('shops', 'products.shop_id', '=', 'shops.id' )
            // shopsのis_sellingとproductsのis_sellingがtrueのみ
            ->where('shops.is_selling', true)
            ->where('products.is_selling', true)
            ->get();

        return view('user.index', compact('products'));
    }


    public function show($id)
    {
        $product = Product::findOrFail($id);

        return view('user.show', compact('product'));
    }

}

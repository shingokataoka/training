<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Product;
use Illuminate\Support\Facades\DB;
use App\Models\Stock;

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
        // ->get()　は付けない（productsにサブクエリで内部結合するため）
        $stocks = DB::table('t_stocks')
            ->select(
                'product_id',
                DB::raw('sum(quantity) as quantity'),
            )
            ->groupBy('product_id')
            ->having('quantity', '>=', 1);

        // is_selling=trueなshopsを取得。DB::raw()は同様にインジェクションに注意。
        // productsとinner joinするので、カラム名の重複回避で「shop_id」などにしてる。
        // ->get()　は付けない（productsにサブクエリで内部結合するため）
         $shops = DB::table('shops')
                ->select(
                    DB::raw('id as shop_id'),
                    DB::raw('is_selling as shop_is_selling')
                )
                ->where('is_selling', true);

        // 数量が1以上、店舗が販売可能、商品も販売可能な $productsを取得
        $products = Product::
            // $stocksをinner joinしてる
            // quantityが1以上 以外のレコードは消える
            joinSub($stocks, 'stocks', function($join) {
                $join->on('products.id', '=', 'stocks.product_id');
            })
            // $shopsをinner joinしてる
            // shop_is_selling=true 以外のレコードは消える
            ->joinSub($shops, 'shops', function($join) {
                $join->on('products.shop_id', '=', 'shops.shop_id');
            })
            ->where('products.is_selling', true)
            ->get();




        return view('user.index', compact('products'));
    }


    public function show($id)
    {
        $product = Product::findOrFail($id);

        $quantity = Stock::where('product_id', $product->id)->sum('quantity');
        if ($quantity > 9) $quantity = 9;

        return view('user.show', compact('product', 'quantity'));
    }

}

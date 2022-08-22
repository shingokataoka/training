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

        $this->middleware(function($request, $next){
            $id = $request->route()->parameter('item');
            if (!isset($id)) return $next($request);
            // 販売可能なアイテムに含まれるか取得
            $exists = Product::availableItems()->where('product_id', $id)->exists();
            if (!$exists) abort(404);
            return $next($request);
        });
    }

    public function index(Request $request)
    {
        // dd($request->sort);
        $products = Product::availableItems()->sortOrder($request->sort)->get();
 
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

<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use App\Models\Shop;
use Illuminate\Support\Facades\Storage;

class ShopController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:owners');

        $this->middleware(function($request, $next){
            $id = $request->route()->parameter('shop'); // URIの{shop}の値を取得
            if(!is_null($id)) {
                $shopsOwnerId = Shop::findOrFail($id)->owner->id;
                $shopsOwnerId = (int)$shopsOwnerId;
                $ownerId = Auth::id();
                if ($shopsOwnerId !== $ownerId) {
                    abort(404);     // 404画面表示
                }
            }
            return $next($request);
        });
    }

    public function index()
    {
        $ownerId = Auth::id();
        $shops = Shop::where('owner_id', $ownerId)->get();
        return view('owner.shops.index', compact('shops'));
    }

    public function edit($id)
    {
        $shop = Shop::findOrFail($id);
        return view('owner.shops.edit', compact('shop'));
    }

    public function update(Request $request, $id)
    {
        $imageFile = $request->image;   // 一時ファイルを取得

        // 画像ファイルがnullでない　かつ　アップロードに成功してるなら
        if ( !is_null($imageFile) && $imageFile->isValid() ) {
            // 画像ファイルをpublic/shops/内に保存する
            // Storage::putFileでファイル名を自動生成してから保存
            // public内shopsフォルダがなければ自動で作ってくれる
            Storage::putFile('public/shops', $imageFile);

            return redirect()
                ->route('owner.shops.index');
        }
    }
}

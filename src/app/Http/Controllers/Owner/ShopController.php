<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use App\Models\Shop;
use Illuminate\Support\Facades\Storage;
use InterventionImage;
use App\Http\Requests\UploadImageRequest;

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

    public function update(UploadImageRequest $request, $id)
    {
        $imageFile = $request->image;   // 一時ファイルを取得

        // 画像ファイルがnullでない　かつ　アップロードに成功してるなら
        if ( !is_null($imageFile) && $imageFile->isValid() ) {
            // ---リサイズなしでの保存---
            // 画像ファイルをpublic/shops/内に保存する
            // Storage::putFileでファイル名を自動生成してから保存
            // public内shopsフォルダがなければ自動で作ってくれる（第二引数はFileオブジェクトのみ、画像無理）
            // Storage::putFile('public/shops', $imageFile);

            // ---リサイズありでの保存---
            // 一意のファイル名を生成
            $fileName = uniqid(rand(). '_');    // ランダムなユニークIDを生成
            $extension = $imageFile->extension();   // 画像ファイルの拡張子
            $fileNameToStore = $fileName . '.' . $extension;
            // リサイズした画像を生成。resizeの第３引数で縦横比維持
            $resizedImage = InterventionImage::make($imageFile)->resize(1000, 1080, function ($constraint) {
                $constraint->aspectRatio();
            })->encode();
            // 画像を保存（putはファイルを指定、Fileオブジェクトではない）
            Storage::put('public/shops/' . $fileNameToStore, $resizedImage);

            return redirect()
                ->route('owner.shops.index');
        }
    }
}

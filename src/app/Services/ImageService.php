<?php

namespace App\Services;

use InterventionImage;
use Illuminate\Support\Facades\Storage;


class ImageService
{
    public static function upload($imageFile, $folderName)
    {
        // 一意のファイル名を生成
        $fileName = uniqid(rand(). '_');    // ランダムなユニークIDを生成
        $extension = $imageFile->extension();   // 画像ファイルの拡張子
        $fileNameToStore = $fileName . '.' . $extension;
        // リサイズした画像を生成。はみ出る切り捨て余白なし
         $resizedImage = InterventionImage::make($imageFile)->fit(1920,1080)->encode();
        // 画像を保存（putはファイルを指定、Fileオブジェクトではない）
        Storage::put("public/{$folderName}/{$fileNameToStore}", $resizedImage);

        return $fileNameToStore;
    }
}

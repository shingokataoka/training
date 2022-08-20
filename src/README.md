## Udemy Laravel交差

## インストール方法

## インストール後の実施事項
imagesテーブルで扱う画像ファイルは、public/images/products内にproduct1.jpg〜product6.jpgにして保存しています。

php artisan storage:linkでstorageフォルダにリンク後、storage/app/public内にstorageフォルダごと移動して保存してください。

そうするとオーナー管理の「画像管理」ページで画像一覧が表示されるようになります。

ショップの画像はproduct/images/shops内にshop1.jpg〜shop2.jpgに保存しています。

storage/app/public内にshopsフォルダごと移動して保存してください。
ショップの画像が表示されるようになります。

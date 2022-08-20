## Udemy Laravel交差

## ダウンロード

git clone
git clone https://github.com/shingokataoka/laravel_umarshe.git

git clone ブランチをしてダウンロードする場合
git clone -b ブランチ名 https://github.com/shingokataoka/laravel_umarshe.git

もしくはzipファイルでダウンロードしてください

## インストール方法

cd laravel_umarshe
docker-compose.ymlやdocker/app/DockerFileで以下はしています

composer install
npm install
nom run dev

.env.exampleをコピーして.envファイルを作成

.envファイルの中の下記をご利用の環境に合わせて変更してください。

DB_CONNECTION=mysql
DB_HOST=db # mysqlコンテナのサービス名
DB_DATABASE=database
DB_USERNAME=user
DB_PASSWORD=password
DB_ROOT_PASSWORD=root_password

docker環境や他の開発環境でDBを起動した後に

php artisan migrate:fresh --seed

と実行してください。（データベーステーブルとダミーデータが追加されればOK）

（この最後に〜はdocker環境なら多分いらない）
最後にphp artisan key:generate
と入力してキーを生成後、

php artisanserve
で簡易サーバーを立ち上げ、表示確認してください。

## インストール後の実施事項
imagesテーブルで扱う画像ファイルは、public/images/products内にproduct1.jpg〜product6.jpgにして保存しています。

php artisan storage:linkでstorageフォルダにリンク後、storage/app/public内にstorageフォルダごと移動して保存してください。

そうするとオーナー管理の「画像管理」ページで画像一覧が表示されるようになります。

ショップの画像はproduct/images/shops内にshop1.jpg〜shop2.jpgに保存しています。

storage/app/public内にshopsフォルダごと移動して保存してください。
ショップの画像が表示されるようになります。

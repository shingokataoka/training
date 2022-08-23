<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Shop;
use App\Models\SecondaryCategory;
use App\Models\Image;
use App\Models\Stock;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'information',
        'price',
        'sort_order',
        'is_selling',
        'shop_id',
        'secondary_category_id',
        'image1',
        'image2',
        'image3',
        'image4',
    ];

    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }

    public function category()
    {
        return $this->belongsTo(SecondaryCategory::class, 'secondary_category_id');
    }

    public function imageFirst()
    {
        return $this->belongsTo(Image::class, 'image1', 'id');
    }
    public function imageSecond()
    {
        return $this->belongsTo(Image::class, 'image2', 'id');
    }
    public function imageThird()
    {
        return $this->belongsTo(Image::class, 'image3', 'id');
    }
    public function imageForth()
    {
        return $this->belongsTo(Image::class, 'image4', 'id');
    }

    public function stocks()
    {
        return $this->hasMany(Stock::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'carts')
            ->withPivot('id', 'quantity');
    }

    public function scopeAvailableItems($query)
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

        // $queryはProduct::の事と思えば良い。

        return $query->joinSub($stocks, 'stock', function($join){
            $join->on('products.id', '=', 'stock.product_id');
        })
        // shopsとのjoinはshops.is_sellingをwhereで使うため
        ->join('shops', 'products.shop_id', 'shops.id')
        // ここ以下のjoinは各テーブルからid以外のカラム取得のため
        ->join('secondary_categories', 'products.secondary_category_id', '=', 'secondary_categories.id')
        ->join('images as image1', 'products.image1', '=', 'image1.id')
        ->join('images as image2', 'products.image2', '=', 'image2.id')
        ->join('images as image3', 'products.image3', '=', 'image3.id')
        ->join('images as image4', 'products.image4', '=', 'image4.id')
        // 使うカラムを指定
        ->select(
            'products.id as id',
            'products.name as name',
            'products.price as price',
            'products.sort_order',
            'products.information',
            'secondary_categories.name as category',
            'image1.filename as filename',
            'products.created_at as created_at',
        )
        // 販売中shop、販売中productのみに絞り込み
        ->where('shops.is_selling', true)
        ->where('products.is_selling', true);
    }


    public function scopeSortOrder($query, $sortOrder = null)
    {
        if (empty($sortOrder)) $sortOrder = \Constant::SORT_ORDER['recommend'];

        if ($sortOrder === \Constant::SORT_ORDER['recommend']) {
            return $query->orderBy('sort_order', 'asc');
        }
        if ($sortOrder === \Constant::SORT_ORDER['higherPrice']) {
            return $query->orderBy('price', 'desc');
        }
        if ($sortOrder === \Constant::SORT_ORDER['lowerPrice']) {
            return $query->orderBy('price', 'asc');
        }
        if ($sortOrder === \Constant::SORT_ORDER['later']) {
            return $query->orderBy('products.created_at', 'desc');
        }
        if ($sortOrder === \Constant::SORT_ORDER['older']) {
            return $query->orderBy('products.created_at', 'asc');
        }
    }


    public function scopeSelectCategory($query, $categoryId)
    {
        if ($categoryId === '0' or empty($categoryId)) return;
        return $query->where('products.secondary_category_id', $categoryId);
    }

    public function scopeSearchKeyword($query, $keyword)
    {
        // 検索キーワードがないなら処理しない
        if (empty($keyword)) return;

        // 全角スペースを半角スペースに変換
        $spaceConvert = mb_convert_kana($keyword, 's');
        // キーワードを半角スペースで区切る
        $keywords = explode(' ', $spaceConvert);
        // キーワードごとにwhereをかけるが、like句で%%で包んで「含むなら」にする
        foreach($keywords as $word) {
            $query->where('products.name', 'like', '%'.$word.'%');
        }
        return $query;
    }
}

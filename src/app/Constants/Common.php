<?php

namespace App\Constants;

class Common
{
    // 普通にクラス定数を宣言
    const PRODUCT_ADD = '1';
    const PRODUCT_REDUCE = '2'; // 購入により減らす
    const PRODUCT_CANCEL = '3'; // stripeで購入キャンセルなら増やす

    // 連想配列での宣言
    const PRODUCT_LIST = [
        'add' => self::PRODUCT_ADD,
        'reduce' => self::PRODUCT_REDUCE,
        'cancel' => self::PRODUCT_CANCEL,
    ];

    // 商品一覧の表示順
    const ORDER_RECOMMEND = 0;  // おすすめ順
    const ORDER_HIGHER = 1;     // 価格高い順
    const ORDER_LOWER = 2;      // 価格安い順
    const ORDER_LATER = 3;      // 作成日新しい順
    const ORDER_OLDER = 4;      // 作成日古い順

    const SORT_ORDER = [
        'recommend' => self::ORDER_RECOMMEND,
        'higherPrice' => self::ORDER_HIGHER,
        'lowerPrice' => self::ORDER_LOWER,
        'later' => self::ORDER_LATER,
        'older' => self::ORDER_OLDER,
    ];
}



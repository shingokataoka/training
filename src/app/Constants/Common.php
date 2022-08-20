<?php

namespace App\Constants;

class Common
{
    // 普通にクラス定数を宣言
    const PRODUCT_ADD = '1';
    const PRODUCT_REDUCE = '2';
    const PRODUCT_CANCEL = '3';

    // 連想配列での宣言
    const PRODUCT_LIST = [
        'add' => self::PRODUCT_ADD,
        'reduce' => self::PRODUCT_REDUCE,
        'cancel' => self::PRODUCT_CANCEL,
    ];
}



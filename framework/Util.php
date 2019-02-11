<?php
/**
 * Created by PhpStorm.
 * User: ihuanglele<huanglele@yousuowei.cn>
 * Date: 2019-02-11
 * Time: 14:51
 */

namespace fw;


use function is_array;
use function is_object;
use function print_r;
use function var_dump;

class Util
{

    public static function url($uri, $params = [], $host = false, $anchor = '')
    {

    }

    public static function dump(...$arr)
    {
        foreach ($arr as $item) {
            if (is_object($item)) {
                var_dump($item);
            } elseif (is_array($item)) {
                print_r($item);
            } else {
                var_dump($item);
            }
        }
    }

}
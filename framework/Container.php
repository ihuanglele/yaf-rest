<?php
/**
 * Created by PhpStorm.
 * Author: 晃晃<wangchunhui@doweidu.com>
 * Date: 2019-01-17
 * Time: 11:37
 */

namespace fw;


use app\exceptions\RuntimeException;
use Yaf\Request_Abstract;
use Yaf\Response_Abstract;
use function key_exists;

class Container
{

    private static $instances = [];

    public static function set($mark, $obj)
    {
        self::$instances[ $mark ] = $obj;
    }

    public static function get($mark)
    {
        if (key_exists($mark, self::$instances)) {
            return self::$instances[ $mark ];
        } else {
            throw new RuntimeException($mark.'is not exit');
        }
    }

    /**
     * @return Request_Abstract
     * @author 晃晃<wangchunhui@doweidu.com>
     * @time 2019-01-17
     */
    public static function getRequest()
    {
        return self::get('request');
    }

    /**
     * @return Response_Abstract
     * @author 晃晃<wangchunhui@doweidu.com>
     * @time 2019-01-17
     */
    public static function getResponse()
    {
        return self::get('response');
    }

}
<?php
/**
 * Created by PhpStorm.
 * Author: ihuanglele<ihuanglele@yousuowei.cn>
 * Date: 2019-01-17
 * Time: 11:37
 */

namespace fw;


use fw\exceptions\RuntimeException;
use Yaf\Request_Abstract;
use Yaf\Response_Abstract;
use function is_null;
use function is_string;
use function key_exists;

class Container
{

    const SYSLOG = 'syslog';

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
     * @author ihuanglele<ihuanglele@yousuowei.cn>
     * @time 2019-01-17
     */
    public static function getRequest()
    {
        return self::get('request');
    }

    /**
     * @return Response_Abstract
     * @author ihuanglele<ihuanglele@yousuowei.cn>
     * @time 2019-01-17
     */
    public static function getResponse()
    {
        return self::get('response');
    }

    /**
     * @param $name
     * @param $default
     * @return mixed
     * @author ihuanglele<ihuanglele@yousuowei.cn>
     * @time 2019-01-18
     */
    public static function getConfig($name = null, $default = null)
    {
        $r = \Yaf\Application::app()->getConfig()->get($name);
        if ($r) {
            if (is_string($r)) {
                return $r;
            }

            return $r->toArray();
        } else {
            if (is_null($default)) {
                return null;
            }

            return $default;
        }
    }

    /**
     * @return \Yaf\Application
     * @author ihuanglele<ihuanglele@yousuowei.cn>
     * @time 2019-01-18
     */
    public static function getApp()
    {
        return \Yaf\Application::app();
    }

    /**
     * 获取日志记录对象
     * @return Logger
     * @author ihuanglele<ihuanglele@yousuowei.cn>
     * @time 2019-01-21
     */
    public static function getLogger()
    {
        return self::get(self::SYSLOG);
    }

}
<?php
/**
 * Created by PhpStorm.
 * Author: ihuanglele<ihuanglele@yousuowei.cn>
 * Date: 2019-01-17
 * Time: 11:37
 */

namespace fw;


use fw\exception\RuntimeException;
use Yaf\Response_Abstract;
use function explode;
use function is_null;
use function is_string;
use function key_exists;

class Container
{

    const SYSLOG   = 'syslog';
    const SYSCACHE = 'syscache';
    const SYSCONF  = 'sysconf';

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
     * @return Request
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
     * 获取 application.ini 里面的核心配置
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

    /**
     * 获取缓存实例
     * @return Cache
     * @author ihuanglele<ihuanglele@yousuowei.cn>
     * @time 2019-01-25
     */
    public static function getCache()
    {
        return self::get(self::SYSCACHE);
    }

    /**
     * 获取用户配置
     * @param string $key
     * @param null $default
     * @return mixed
     * @author ihuanglele<huanglele@yousuowei.cn>
     */
    public static function getConf($key = '', $default = null)
    {
        $arr = self::get(self::SYSCONF);
        if (empty($key)) {
            return $arr;
        }
        $path = explode('#', $key);
        foreach ($path as $p) {
            if (key_exists($p, $arr)) {
                $arr     = $arr[ $p ];
                $default = $arr;
            } else {
                $default = null;
                break;
            }
        }

        return $default;
    }

}
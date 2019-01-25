<?php
/**
 * Created by PhpStorm.
 * Author: 晃晃<wangchunhui@doweidu.com>
 * Date: 2019-01-25
 * Time: 11:11
 */

namespace fw;


class Cache
{

    private $driver;

    public static function setDriver($cache)
    {
        if (empty($cache)) {
            $cache = 'File';
        }
        if (is_string($cache)) {
            if (false === strpos($cache, '\\')) {
                $cache = 'fw\\cache\\'.$cache;
            }
            if (class_exists($cache)) {
                $obj = new $cache();
                if ($obj instanceof Cache) {
                    $cacheIns         = new self();
                    $cacheIns->driver = $obj;

                    return $cacheIns;
                }
            } else {
                die("cache drive ${cache} is not found");
            }
        } elseif ($cache instanceof Cache) {
            $cacheIns         = new self();
            $cacheIns->driver = $cache;

            return $cacheIns;
        } else {
            die('cache init error');
        }
    }

    private function __construct()
    {
    }

    /**
     * @param $key
     * @param $value
     * @param int $expire 过期时间
     * @author 晃晃<wangchunhui@doweidu.com>
     * @time 2019-01-25
     */
    public function set($key, $value, $expire)
    {
        $this->driver->set($key, $value, $expire);
    }

    /**
     * @param $key
     * @return mixed
     * @author 晃晃<wangchunhui@doweidu.com>
     * @time 2019-01-25
     */
    public function get($key)
    {
        $this->driver->get($key);
    }

    /**
     * @param $key
     * @return bool
     * @author 晃晃<wangchunhui@doweidu.com>
     * @time 2019-01-25
     */
    public function has($key)
    {
        return $this->has($key);
    }

}
<?php
/**
 * Created by PhpStorm.
 * Author: 晃晃<wangchunhui@doweidu.com>
 * Date: 2019-01-25
 * Time: 11:35
 */

namespace fw\cache;


use fw\Cache;
use function file_exists;
use function file_get_contents;
use function file_put_contents;
use function is_numeric;
use function serialize;
use function time;
use function unlink;
use function unserialize;
use const DIRECTORY_SEPARATOR;

class File extends Cache
{

    protected $path = '';

    public function __construct()
    {
        $this->path = APPLICATION_PATH.DIRECTORY_SEPARATOR.'runtime'.DIRECTORY_SEPARATOR.'cache'.DIRECTORY_SEPARATOR;
    }

    public function get($key)
    {
        if (!file_exists($this->path.$key)) {
            return null;
        }
        $c = file_get_contents($this->path.$key);
        if (!$c) {
            return null;
        }
        $arr = unserialize($c);
        if ($arr['expire'] && $arr['expire'] && $arr['expire'] < time()) {
            unlink($this->path.$key);
            return null;
        }

        return $arr['data'];
    }

    public function has($key)
    {
        $r = $this->get($key);
        if (null !== $r) {
            return true;
        } else {
            return false;
        }
    }

    public function set($key, $value, $expire)
    {
        if (!is_numeric($expire)) {
            $expire = 0;
        }
        file_put_contents($this->path.$key,
                          serialize(['expire' => $expire, 'data' => $value]));
    }

}
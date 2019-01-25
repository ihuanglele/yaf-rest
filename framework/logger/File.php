<?php
/**
 * Created by PhpStorm.
 * User: ihuanglele<huanglele@yousuowei.cn>
 * Date: 2019-01-18
 * Time: 17:46
 */

namespace fw\logger;


use fw\Logger;
use function date;
use function file_put_contents;
use function json_encode;
use const DIRECTORY_SEPARATOR;
use const FILE_APPEND;
use const JSON_UNESCAPED_UNICODE;

class File extends Logger
{

    protected $path = '';

    private static $logs = [];

    public function __construct()
    {
        $this->path = APPLICATION_PATH.DIRECTORY_SEPARATOR.'runtime'.DIRECTORY_SEPARATOR.date('Y-m-d').'.log';
    }

    /**
     * Logs with an arbitrary level.
     *
     * @param mixed $level
     * @param string $message
     * @param array $context
     *
     * @return void
     */
    public function log($level, $message, array $context = [])
    {
        if (empty($context)) {
            static::$logs[ $level ][] = $message;
        } else {
            static::$logs[ $level ][ $message ] = $context;
        }
    }

    /**
     * 写入缓存
     * @author ihuanglele<ihuanglele@yousuowei.cn>
     * @time 2019-01-25
     */
    public function write()
    {
        if (!empty(static::$logs)) {
            file_put_contents($this->path, json_encode(static::$logs, JSON_UNESCAPED_UNICODE)."\r\n", FILE_APPEND);
            static::$logs = [];
        }
    }
}
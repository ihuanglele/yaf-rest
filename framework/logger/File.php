<?php
/**
 * Created by PhpStorm.
 * User: ihuanglele<huanglele@yousuowei.cn>
 * Date: 2019-01-18
 * Time: 17:46
 */

namespace fw\logger;


use function date;
use function file_put_contents;
use function json_encode;
use function key_exists;
use const DIRECTORY_SEPARATOR;
use const JSON_UNESCAPED_UNICODE;

class File extends \Psr\Log\AbstractLogger
{

    protected $path = '';

    public function __construct($config = [])
    {
        if (key_exists('path', $config)) {

        } else {
            //            $this->path = APPLICATION_PATH.'runtime'.DIRECTORY_SEPARATOR.date('Y-m-d').'.log';
        }
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
        // TODO: Implement log() method.
        file_put_contents($this->path,
                          json_encode([$level => ['msg' => $message, 'data' => $context]], JSON_UNESCAPED_UNICODE).
                          "\r\n");
    }
}
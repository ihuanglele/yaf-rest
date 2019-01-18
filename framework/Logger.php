<?php

namespace fw;


use Psr\Log\LoggerInterface;

class Logger
{

    /**
     * @var LoggerInterface
     */
    private static $logger;

    private static function getLogger()
    {
        return self::$logger;
    }

    /**
     * @param $msg
     * @param $data
     * @author ihuanglele<ihuanglele@yousuowei.cn>
     * @time 2019-01-18
     */
    public static function info($msg, $data = [])
    {
        self::getLogger()->info($msg, $data);
    }

    public static function error($message, $context = [])
    {
        self::getLogger()->error($message, $context);
    }

    public static function emergency($message, $context = [])
    {
        self::getLogger()->emergency($message, $context);
    }

    public static function debug($message, $context = [])
    {
        self::getLogger()->debug($message, $context);
    }

    public static function notice($message, $context = [])
    {
        self::getLogger()->notice($message, $context);
    }

    public static function warning($message, $context = [])
    {
        self::getLogger()->warning($message, $context);
    }

    public static function critical($message, $context = [])
    {
        self::getLogger()->critical($message, $context);
    }

    public static function alert($message, $context = [])
    {
        self::getLogger()->alert($message, $context);
    }

}
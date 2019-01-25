<?php

namespace fw;


use Psr\Log\LoggerInterface as LoggerInterface;
use function class_exists;
use function is_string;

class Logger implements LoggerInterface
{

    /**
     * @var Logger
     */
    private $logger;

    /**
     * 初始化 logger
     * @param string|Logger $logger
     * @return Logger
     * @author ihuanglele<ihuanglele@yousuowei.cn>
     * @time 2019-01-21
     */
    public static function setLogger($logger = null)
    {
        if (empty($logger)) {
            $logger = 'File';
        }
        if (is_string($logger)) {
            if (false === strpos($logger, '\\')) {
                $logger = 'fw\\logger\\'.$logger;
            }
            if (class_exists($logger)) {
                $obj = new $logger();
                if ($obj instanceof Logger) {
                    $log         = new self();
                    $log->logger = $obj;

                    return $log;
                }
            } else {
                die("logger drive ${logger} is not found");
            }
        } elseif ($logger instanceof Logger) {
            $log         = new self();
            $log->logger = $logger;

            return $log;
        } else {
            die('logger init error');
        }
    }

    private function __construct()
    {
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
        $this->logger->log($level, $message, $context);
    }

    /**
     * System is unusable.
     *
     * @param string $message
     * @param array $context
     *
     * @return void
     */
    public function emergency($message, array $context = [])
    {
        $this->logger->log('emergency', $message, $context);
    }

    /**
     * Action must be taken immediately.
     *
     * Example: Entire website down, database unavailable, etc. This should
     * trigger the SMS alerts and wake you up.
     *
     * @param string $message
     * @param array $context
     *
     * @return void
     */
    public function alert($message, array $context = [])
    {
        $this->logger->log('alert', $message, $context);
    }

    /**
     * Critical conditions.
     *
     * Example: Application component unavailable, unexpected exception.
     *
     * @param string $message
     * @param array $context
     *
     * @return void
     */
    public function critical($message, array $context = [])
    {
        $this->logger->log('critical', $message, $context);
    }

    /**
     * Runtime errors that do not require immediate action but should typically
     * be logged and monitored.
     *
     * @param string $message
     * @param array $context
     *
     * @return void
     */
    public function error($message, array $context = [])
    {
        $this->logger->log('error', $message, $context);
    }

    /**
     * Exceptional occurrences that are not errors.
     *
     * Example: Use of deprecated APIs, poor use of an API, undesirable things
     * that are not necessarily wrong.
     *
     * @param string $message
     * @param array $context
     *
     * @return void
     */
    public function warning($message, array $context = [])
    {
        $this->logger->log('warning', $message, $context);
    }

    /**
     * Normal but significant events.
     *
     * @param string $message
     * @param array $context
     *
     * @return void
     */
    public function notice($message, array $context = [])
    {
        $this->logger->log('notice', $message, $context);
    }

    /**
     * Interesting events.
     *
     * Example: User logs in, SQL logs.
     *
     * @param string $message
     * @param array $context
     *
     * @return void
     */
    public function info($message, array $context = [])
    {
        $this->logger->log('info', $message, $context);
    }

    /**
     * Detailed debug information.
     *
     * @param string $message
     * @param array $context
     *
     * @return void
     */
    public function debug($message, array $context = [])
    {
        $this->logger->log('debug', $message, $context);
    }

    /**
     * 日志写入
     * @author ihuanglele<ihuanglele@yousuowei.cn>
     * @time 2019-01-21
     */
    public function write()
    {
        $this->logger->write();
    }
}
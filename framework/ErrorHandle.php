<?php
/**
 * Created by PhpStorm.
 * User: ihuanglele<huanglele@yousuowei.cn>
 * Date: 2019-01-21
 * Time: 14:42
 */

namespace fw;


use Exception;
use function json_encode;

class ErrorHandle
{

    /**
     * Error Handler
     * @param  integer $errno 错误编号
     * @param  integer $errstr 详细错误信息
     * @param  string $errfile 出错的文件
     * @param  integer $errline 出错行号
     * @author ihuanglele<ihuanglele@yousuowei.cn>
     * @time 2019-01-21
     */
    public static function appError($errno, $errstr, $errfile = '', $errline = 0)
    {
        Container::getLogger()->error('fatal error',
                                      [
                                          'errno'   => $errno,
                                          'errstr'  => $errstr,
                                          'errfile' => $errfile,
                                          'errline' => $errline,
                                      ]);
    }

    /**
     * Exception Handler
     * @param Exception $e
     * @author ihuanglele<ihuanglele@yousuowei.cn>
     * @time 2019-01-21
     */
    public static function appException($e)
    {
        Container::getLogger()->error('fatal exception',
                                      [
                                          'msg'   => $e->getMessage(),
                                          'code'  => $e->getCode(),
                                          'file'  => $e->getFile(),
                                          'line'  => $e->getLine(),
                                          'trace' => $e->getTrace(),
                                      ]);
        $res = new \Yaf\Response\Http();
        $res->setBody(json_encode([
                                      'code'  => 0,
                                      'msg'   => 'sys error',
                                      'error' => $e->getMessage(),
                                  ]));
        $res->response();
    }


}
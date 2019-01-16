<?php

use app\exceptions\RequestException;

/**
 * Created by PhpStorm.
 * Author: 晃晃<wangchunhui@doweidu.com>
 * Date: 2019-01-16
 * Time: 14:38
 */

class Bootstrap extends \Yaf\Bootstrap_Abstract {

    public function _initConfig(\Yaf\Dispatcher $dispatcher) {

        // 记录日志
        register_shutdown_function(function(){

        });

        set_exception_handler(function($e){
            if($e instanceof RequestException){
                // 正常输出
            }else{
                // 判断应用类型
            }
        });
    }

    public function _initPlugin(\Yaf\Dispatcher $dispatcher) {
        $dispatcher->disableView();
    }
}
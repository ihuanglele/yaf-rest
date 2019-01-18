<?php

use app\exceptions\RequestException;
use fw\AppPlugin;

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
            if ($e instanceof \Exception) {
                if ($e instanceof RequestException) {
                    // 正常输出

                } else {
                    // 判断应用类型
                    echo json_encode(['code' => 0, 'msg' => $e->getMessage()]);
                }
            } else {
                // 抛出 error

            }
        });
    }

    public function _initPlugin(\Yaf\Dispatcher $dispatcher)
    {
        $plugins = \Yaf\Application::app()->getConfig()->get('plugins');
        if (!empty($plugins)) {
            $arr = [];
            if (is_string($plugins)) {
                $arr[] = $plugins;
            } elseif (is_array($plugins)) {
                $arr = $plugins;
            }
            foreach ($plugins as $plugin) {
                if (class_exists($plugin, false)) {
                    $obj = new $plugin();
                    if ($obj instanceof \Yaf\Plugin_Abstract) {
                        $dispatcher->registerPlugin(new $obj);
                    } else {
                        $obj = null;
                    }
                } else {
                    echo $plugin.' is not found';
                }
            }
        }
        $dispatcher->registerPlugin(new AppPlugin());
    }

    public function _initView(\Yaf\Dispatcher $dispatcher){
        $dispatcher->disableView();
    }

}
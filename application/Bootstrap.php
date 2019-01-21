<?php

use fw\AppPlugin;
use fw\Container;
use fw\Logger;

/**
 * Created by PhpStorm.
 * Author: ihuanglele<ihuanglele@yousuowei.cn>
 * Date: 2019-01-16
 * Time: 14:38
 */

class Bootstrap extends \Yaf\Bootstrap_Abstract {

    public function _initApp()
    {
        $log    = Container::getConfig('log', 'fw\\logger\\File');
        $logger = Logger::setLogger($log);
        Container::set(Container::SYSLOG, $logger);
        register_shutdown_function(function()
        {
            Container::getLogger()->write();
        });
        set_error_handler(['fw\\ErrorHandle', 'appError']);
        set_exception_handler(['fw\\ErrorHandle', 'appException']);
    }

    /**
     * 注册插件
     * @param \Yaf\Dispatcher $dispatcher
     * @author ihuanglele<ihuanglele@yousuowei.cn>
     * @time 2019-01-18
     */
    public function _initPlugin(\Yaf\Dispatcher $dispatcher)
    {
        $plugins = Container::getConfig('plugins');
        if (!empty($plugins)) {
            $arr = [];
            if (is_string($plugins)) {
                $arr[] = $plugins;
            } elseif (is_array($plugins)) {
                $arr = $plugins;
            }
            foreach ($plugins as $plugin) {
                if (class_exists($plugin)) {
                    $obj = new $plugin();
                    if ($obj instanceof \Yaf\Plugin_Abstract) {
                        $dispatcher->registerPlugin(new $obj);
                    } else {
                        Container::getLogger()->info("plugin ${plugin} should be \Yaf\Plugin_Abstract");
                        $obj = null;
                    }
                } else {
                    Container::getLogger()->info("plugin ${plugin} is not exist");
                }
            }
        }
        $dispatcher->registerPlugin(new AppPlugin());
    }

    /**
     * 默认不使用 view 渲染
     * @param \Yaf\Dispatcher $dispatcher
     * @author ihuanglele<ihuanglele@yousuowei.cn>
     * @time 2019-01-18
     */
    public function _initView(\Yaf\Dispatcher $dispatcher){
        $dispatcher->disableView();
    }

}
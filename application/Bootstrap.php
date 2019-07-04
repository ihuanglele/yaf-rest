<?php

use fw\AppPlugin;
use fw\Cache;
use fw\CliRequest;
use fw\Container;
use fw\Logger;
use fw\Request;
use fw\SysConst;

/**
 * Created by PhpStorm.
 * Author: ihuanglele<ihuanglele@yousuowei.cn>
 * Date: 2019-01-16
 * Time: 14:38
 */

class Bootstrap extends \Yaf\Bootstrap_Abstract {

    public function _initApp()
    {
        if (PHP_SAPI == 'cli') {
            define('CLI', true);

            list($controller, $action) = explode('/', $_SERVER['argv'][1]);
            Container::getApp()->getDispatcher()->setRequest(new \Yaf\Request\Simple('cli', 'Script', $controller, $action));
        } else {
            define('CLI', false);
            Container::getApp()->getDispatcher()->setRequest(new Request());
        }
        $t_start = microtime(true);
        $log     = Container::getConfig('log', 'fw\\logger\\File');
        $logger  = Logger::setLogger($log);
        // 注册日志实例
        Container::set(Container::SYSLOG, $logger);
        $cache    = Container::getConfig('cache');
        $cacheIns = Cache::setDriver($cache);
        // 注册缓存实例
        Container::set(Container::SYSCACHE, $cacheIns);
        register_shutdown_function(function() use ($t_start)
        {
            $t_end     = microtime(true);
            $mem_usage = memory_get_usage();
            if ($mem_usage < 1024) {
                $mem_usage .= " bytes";
            } elseif ($mem_usage < 1048576) {
                $mem_usage = round($mem_usage / 1024, 2)." KB";
            } else {
                $mem_usage = round($mem_usage / 1048576, 2)." M";
            }
            $info = [
                'time'   => round($t_end - $t_start, 4),
                'memory' => $mem_usage,
            ];
            Container::getLogger()->info('runtime', $info);
            Container::getLogger()->write();
        });
        set_error_handler(['fw\\ErrorHandle', 'appError']);
        set_exception_handler(['fw\\ErrorHandle', 'appException']);
    }

    /**
     * 注册自定义配置
     * @author ihuanglele<huanglele@yousuowei.cn>
     */
    public function _initConfig()
    {
        $files = Container::getConfig(SysConst::CONFIGURATIONS_KEY, []);
        if (empty($files)) {
            return;
        }
        if (is_string($files)) {
            $files = explode(',', $files);
        }
        $arr = [];
        foreach ($files as $file) {
            // 判断文件是否存在
            if (is_file(CONF_PATH . DS . $file)) {
                // 判断文件类型
                $type = substr($file, -3);
                $t    = [];
                if ('php' === $type) {
                    $t = include(CONF_PATH . DS . $file);
                } elseif ('ini' === $type) {
                    $t = parse_ini_file(CONF_PATH . DS . $file, true);
                }
                if (is_array($t)) {
                    $key         = substr($file, 0, -4);
                    $arr[ $key ] = $t;
                }
            }
        }
        Container::set(Container::SYSCONF, $arr);
    }

    public function _initAppBootstrap(\Yaf\Dispatcher $dispatcher)
    {
        if (file_exists(APP_PATH . 'AppBootstrap.php')) {
            include APP_PATH . 'AppBootstrap.php';
            if (class_exists('\\AppBootstrap')) {
                $appBootstrap = new \AppBootstrap();
                $appReflection = new ReflectionClass('\\AppBootstrap');
                $methods = $appReflection->getMethods(ReflectionMethod::IS_PUBLIC);
                foreach ($methods as $method) {
                    $name = $method->getName();
                    if (0 === strpos($name, '_init')) {
                        $appBootstrap->$name($dispatcher);
                    }
                }
            }

        }
    }

    // 注册自定义路由
    public function _initRouter(\Yaf\Dispatcher $dispatcher)
    {
        if (key_exists('routes', Container::get(Container::SYSCONF))) {
            $dispatcher->getRouter()->addConfig(Container::get(Container::SYSCONF)['routes']);
        }
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
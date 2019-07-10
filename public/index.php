<?php
/**
 * Created by PhpStorm.
 * Author: ihuanglele<ihuanglele@yousuowei.cn>
 * Date: 2019-01-16
 * Time: 14:18
 */


define('DS', DIRECTORY_SEPARATOR);
define('ROOT_PATH', dirname(__DIR__) . DS);
define('APP_PATH', ROOT_PATH . 'application' . DS);
define('CONF_PATH', ROOT_PATH . 'conf' . DS);
date_default_timezone_set('Asia/Shanghai');
require ROOT_PATH . 'vendor/autoload.php';

$app = new \Yaf\Application(CONF_PATH . 'application.ini');
$app->bootstrap() //call bootstrap methods defined in Bootstrap.php
->run();
<?php
/**
 * Created by PhpStorm.
 * Author: 晃晃<wangchunhui@doweidu.com>
 * Date: 2019-01-16
 * Time: 14:18
 */

define('APPLICATION_PATH', dirname(__DIR__));

require APPLICATION_PATH.'/vendor/autoload.php';

$app  = new \Yaf\Application(APPLICATION_PATH . "/conf/application.ini");
$app->bootstrap() //call bootstrap methods defined in Bootstrap.php
    ->run();
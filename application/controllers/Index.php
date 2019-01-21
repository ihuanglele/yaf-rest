<?php

use fw\Container;

/**
 * Created by PhpStorm.
 * Author: ihuanglele<ihuanglele@yousuowei.cn>
 * Date: 2019-01-16
 * Time: 14:24
 */

class IndexController extends BaseController
{

    public function indexAction(){
        Container::getLogger()->info('test', ['ok' => time()]);
    }

    public function errorAction(){
        throw new \Yaf\Exception('on');
    }

    public function emptyAction()
    {

    }

}
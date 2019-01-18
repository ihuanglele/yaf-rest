<?php
/**
 * Created by PhpStorm.
 * Author: ihuanglele<ihuanglele@yousuowei.cn>
 * Date: 2019-01-16
 * Time: 14:24
 */

class IndexController extends BaseController
{

    public function indexAction(){
        $loader = \Yaf\Loader::getInstance();

        //        $this->success('success');
    }

    public function errorAction(){
        throw new \Yaf\Exception();
    }

    public function emptyAction()
    {

    }

}
<?php
/**
 * Created by PhpStorm.
 * Author: 晃晃<wangchunhui@doweidu.com>
 * Date: 2019-01-16
 * Time: 14:24
 */

class IndexController extends BaseController
{

    public function indexAction(){
        $this->success('success');
    }

    public function errorAction(){
        throw new \Yaf\Exception();
    }

}
<?php
/**
 * Created by PhpStorm.
 * Author: 晃晃<wangchunhui@doweidu.com>
 * Date: 2019-01-16
 * Time: 14:40
 */

class BaseController extends \Yaf\Controller_Abstract
{

    public function init()
    {

    }

    protected function success($data){
        $this->jsonView(['code' => 200,'data' => $data]);
    }

    protected function error($msg,$code = 0){
        $this->jsonView(['code' => $code,$msg => $msg]);
    }

    private function jsonView($body){
        $this->getResponse()->setBody(json_encode($body,JSON_UNESCAPED_UNICODE));
    }

}
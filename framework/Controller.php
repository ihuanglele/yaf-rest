<?php
/**
 * Created by PhpStorm.
 * User: ihuanglele<huanglele@yousuowei.cn>
 * Date: 2019-01-21
 * Time: 16:06
 */

namespace fw;

use function json_encode;
use const JSON_UNESCAPED_UNICODE;

class Controller extends \Yaf\Controller_Abstract
{

    public function init()
    {
        // add Header
        $this->getResponse()->setHeader('Content-Type', 'application/json;charset=utf-8');
        $this->getResponse()->setHeader('Server', 'Apache/1.8.0');
        $this->getResponse()->setHeader('X-Powered-By', 'YafRest');
        $this->getResponse()->setHeader('Access-Control-Allow-Origin', '*');
        $this->getResponse()->setHeader('Access-Control-Allow-Methods', 'GET,OPTIONS,POST,HEAD,DELETE');
        $this->getResponse()->setHeader('Access-Control-Allow-Credentials', 'true');
    }

    /**
     * 成功返回
     * @param string|array $data
     * @author ihuanglele<ihuanglele@yousuowei.cn>
     * @time 2019-01-21
     */
    protected function success($data)
    {
        $this->getResponse()->setBody(json_encode(['code' => 200, 'data' => $data], JSON_UNESCAPED_UNICODE));
    }

    /**
     * 失败返回
     * @param string $msg
     * @param int $code
     * @author ihuanglele<ihuanglele@yousuowei.cn>
     * @time 2019-01-21
     */
    protected function error($msg, $code = 0)
    {
        $this->getResponse()->setBody(json_encode(['code' => $code, 'msg' => $msg], JSON_UNESCAPED_UNICODE));
    }

}
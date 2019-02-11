<?php
/**
 * Created by PhpStorm.
 * Author ihuanglele<huanglele@yousuowei.cn>
 * Date: 2019-02-01
 * Time: 16:47
 */

namespace fw;

/**
 * 封装一层 Request 用于统一参数、参数过滤
 */

use function explode;
use function implode;
use function key_exists;
use function ltrim;
use function parse_str;
use function ucfirst;

class Request extends \Yaf\Request\Http
{

    private $isInit = false;

    protected $_get = [];

    public function __construct(string $request_uri = '', string $base_uri = '')
    {
        if (empty($request_uri)) {
            list($request_uri) = explode('?', $_SERVER['REQUEST_URI']);
        }
        parent::__construct($request_uri, $base_uri);
    }

    /**
     * 处理 uri 参数
     * @author ihuanglele<ihuanglele@yousuowei.cn>
     * @time 2019-02-11
     */
    public function init()
    {
        if ($this->isInit) {
            return;
        }
        if ($this->routed) {
            list($uri, $param) = explode('?', $_SERVER['REQUEST_URI']);
            $uri = ucfirst(ltrim($uri, '/'));
            $uri = ucfirst(preg_replace('/^'.$this->getModuleName().'\//', '', $uri));     //  去掉 module
            $uri = lcfirst(preg_replace('/^'.$this->getControllerName().'\//', '', $uri));     //  去掉 controller
            $uri = preg_replace('/^'.$this->getActionName().'/', '', $uri);     //  去掉 action
            $uri = ltrim($uri, '/');

            $arr = [];
            $i   = 0;
            $k   = '';
            foreach (explode('/', $uri) as $s) {
                if ($i === 0) {
                    $k = $s;
                    $i = 1;
                } else {
                    $i     = 0;
                    $arr[] = $k.'='.$s;
                }
            }
            $str = implode('&', $arr);
            parse_str(implode('&', [$str, $param]), $this->_get);
        }
    }

    public function get($name = null, $default = null)
    {
        $this->init();
        if (null === $name) {
            return $this->_get;
        }
        if (key_exists($name, $this->_get)) {
            return $this->_get[ $name ];
        } else {
            return $default;
        }
    }

}
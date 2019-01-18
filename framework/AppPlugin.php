<?php
/**
 * Created by PhpStorm.
 * Author: 晃晃<wangchunhui@doweidu.com>
 * Date: 2019-01-17
 * Time: 10:50
 */

namespace fw;

use Yaf\Plugin_Abstract;
use Yaf\Request_Abstract;
use Yaf\Response_Abstract;
use function file_put_contents;
use function var_dump;

class AppPlugin extends Plugin_Abstract
{

    /**
     * 这个是7个事件中, 最早的一个. 但是一些全局自定的工作, 还是应该放在Bootstrap中去完成
     * 在路由之前触发
     * @param Request_Abstract $request
     * @param Response_Abstract $response
     * @return bool|void
     * @author 晃晃<wangchunhui@doweidu.com>
     * @time 2019-01-17
     */
    public function routerStartup(Request_Abstract $request, Response_Abstract $response)
    {
        Container::set('request', $request);
        Container::set('response', $response);
    }

    /**
     * 此时路由一定正确完成, 否则这个事件不会触发
     * @param Request_Abstract $request
     * @param Response_Abstract $response
     * @return bool|void
     * @author 晃晃<wangchunhui@doweidu.com>
     * @time 2019-01-17
     */
    public function routerShutdown(Request_Abstract $request, Response_Abstract $response)
    {

    }

    /**
     * 分发循环开始之前被触发
     * @param Request_Abstract $request
     * @param Response_Abstract $response
     * @return bool|void
     * @author 晃晃<wangchunhui@doweidu.com>
     * @time 2019-01-17
     */
    public function dispatchLoopStartup(Request_Abstract $request, Response_Abstract $response)
    {

    }

    /**
     * 此时表示所有的业务逻辑都已经运行完成, 但是响应还没有发送
     * 分发循环结束之后触发
     * @param Request_Abstract $request
     * @param Response_Abstract $response
     * @return bool|void
     * @author 晃晃<wangchunhui@doweidu.com>
     * @time 2019-01-17
     */
    public function dispatchLoopShutdown(Request_Abstract $request, Response_Abstract $response)
    {
        $body = $response->getBody();
        var_dump($response === Container::getResponse());
        file_put_contents('body.txt', $body);
    }

    /**
     * 如果在一个请求处理过程中, 发生了forward, 则这个事件会被触发多次
     * 分发之前触发
     * @param Request_Abstract $request
     * @param Response_Abstract $response
     * @return bool|void
     * @author 晃晃<wangchunhui@doweidu.com>
     * @time 2019-01-17
     */
    public function preDispatch(Request_Abstract $request, Response_Abstract $response)
    {

    }

    /**
     * 此时动作已经执行结束, 视图也已经渲染完成. 和preDispatch类似, 此事件也可能触发多次
     * 分发结束之后触发
     * @param Request_Abstract $request
     * @param Response_Abstract $response
     * @return bool|void
     * @author 晃晃<wangchunhui@doweidu.com>
     * @time 2019-01-17
     */
    public function postDispatch(Request_Abstract $request, Response_Abstract $response)
    {

    }

    public function preResponse(Request_Abstract $request, Response_Abstract $response)
    {

    }
}
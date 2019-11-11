<?php
/**
 * Created by PhpStorm.
 * User: ihuanglele<huanglele@yousuowei.cn>
 * Date: 2019-11-11
 * Time: 11:06
 */


/**
 * 使用 thinkPHP cache
 * @see https://github.com/top-think/think-cache
 */
return [
    'default' => 'file',
    'stores'  => [
        // 文件缓存
        'file'  => [
            // 驱动方式
            'type' => 'file',
            // 设置不同的缓存保存目录
            'path' => '../runtime/file/',
        ],
        // redis缓存
        'redis' => [
            // 驱动方式
            'type' => 'redis',
            // 服务器地址
            'host' => '127.0.0.1',
        ],
    ],
];
<?php
/**
 * Created by PhpStorm.
 * User: ihuanglele<huanglele@yousuowei.cn>
 * Date: 2019-01-31
 * Time: 16:09
 */

namespace fw;


use fw\exceptions\RuntimeException;
use Medoo\Medoo;
use function key_exists;
use function method_exists;

class Model extends Medoo
{
    private static $instances = [];

    protected $type = 'mysql';

    /**
     * @var Medoo
     */
    protected $instance = null;

    public function __construct($type = '')
    {
        if (!empty($type)) {
            $this->type = $type;
        }
        if (key_exists($this->type, self::$instances)) {
            $this->instance = self::$instances[ $this->type ];
        } else {
            // 读取配置的 数据库连接配置
            $options                        = Container::getConf('database#'.$this->type);
            $this->instance                 = parent::__construct($options);
            self::$instances[ $this->type ] = $this->instance;
        }
    }

    public function __call($name, $arguments)
    {
        // TODO: Implement __call() method.
        if (method_exists($this->instance, $name)) {
            $this->instance->$name($arguments);
        } else {
            throw new RuntimeException('Function '.$name.' is not found');
        }
    }

}
<?php
/**
 * Created by PhpStorm.
 * User: ihuanglele<huanglele@yousuowei.cn>
 * Date: 2019-01-31
 * Time: 16:09
 */

namespace fw;

use fw\exception\RuntimeException;
use Medoo\Medoo;
use function defined;
use function in_array;
use function key_exists;
use function method_exists;
use function str_replace;

/**
 * Class Model
 * @package fw
 * @mixin Medoo
 * @method Medoo select($join, $columns = null, $where = null) 查询列表
 * @method Medoo insert($datas) 插入数据
 * @method Medoo update($data, $where = null) 更新数据
 * @method Medoo delete($where) 删除数据
 * @method Medoo replace($columns, $where = null) Replace old data into new one看（不知道干啥用的搬过来）
 * @method Medoo get($join = null, $columns = null, $where = null) 获取单条数据
 * @method Medoo has($join, $where = null)
 * @method Medoo rand($join = null, $columns = null, $where = null)
 * @method Medoo count($join = null, $column = null, $where = null)
 * @method Medoo avg($join = null, $column = null, $where = null)
 * @method Medoo max($join = null, $column = null, $where = null)
 * @method Medoo min($join = null, $column = null, $where = null)
 * @method Medoo sum($join = null, $column = null, $where = null)
 */
class Model
{
    // 表名
    const TABLE = '';

    // 数据库实例数组 键值为实例类型
    private static $instances = [];

    protected $type = 'mysql';

    /**
     * 当前 model 的 Medoo 实例
     * @var Medoo
     */
    protected $instance = null;

    private static $formatMethods = [
        'select',
        'insert',
        'update',
        'delete',
        'replace',
        'get',
        'has',
        'rand',
        'count',
        'avg',
        'max',
        'min',
        'sum',
    ];

    /**
     * Model constructor.
     * @param string $type 数据库连接配置键
     */
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
            $this->instance                 = new Medoo($options);
            self::$instances[ $this->type ] = $this->instance;
        }
    }

    /**
     * 通过 PHP 魔术方法 调用 Medoo 方法
     * @param $name
     * @param $arguments
     * @return mixed
     * @author ihuanglele<huanglele@yousuowei.cn>
     */
    public function __call($name, $arguments)
    {
        // TODO: Implement __call() method.
        if (method_exists($this->instance, $name)) {
            if (in_array($name, self::$formatMethods)) {
                return $this->instance->$name(static::getTable(), ...$arguments);
            } else {
                return $this->instance->$name(...$arguments);
            }
        } else {
            throw new RuntimeException('Function '.$name.' is not found');
        }
    }

    /**
     * 获取当前 Model 对应需要操作的表
     * @return string
     * @author ihuanglele<huanglele@yousuowei.cn>
     */
    public static function getTable()
    {
        if (defined('static::TABLE')) {
            return static::TABLE;
        } else {
            $name = str_replace('\\', '/', static::class);

            return strtolower(preg_replace('/([a-z])([A-Z])/', "$1".' '."$2", basename($name)));
        }
    }

}
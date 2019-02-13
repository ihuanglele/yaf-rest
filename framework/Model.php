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
use function explode;
use function function_exists;
use function in_array;
use function is_array;
use function is_numeric;
use function is_string;
use function json_decode;
use function key_exists;
use function method_exists;
use function preg_match;
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

    private $dataListSearchFields = [
        // get 字段
        'example'    => [
            // 数据库映射字段 缺省为 get 字段
            'field'     => '',
            // 默认 value 值：没有获取到get值时，取default值；缺省为空
            'default'   => '',
            /**
             * 对get值 进行过滤
             * 自定义、内置函数 $value = fun($value)
             */
            'filter'    => '',
            /**
             * 触发搜索的表达式
             *  默认直接 if 判断 field 过滤之后的值
             *  #exp($field > 1)  包裹表达式 $field 会被替换为参数过滤后的值 $value
             *  内置、自定义函数 fun($value)  判断返回值
             *  closure 闭包函数 判断返回值 function($key,$field,$value)
             */
            'condition' => '',
            // 搜索方式
            'op'        => '',
            /**
             * 搜索表达式值
             * 缺省为过滤后的值  支持： #exp 表达式、闭包函数、自定义函数、内置函数
             */
            'exp'       => '',
        ],
        'cat_id' => [
            'default'   => -1,
            'condition' => '#exp($field >= 0)',
            'op'        => '=',
            'exp'       => '$field',
        ],
        'is_checked' => 'number',   // 匹配数字，同cat_id
        'status' => [
            'default'   => -1,
            'filter'    => 'intval',
            'condition' => '#exp(in_array($field,[0,1]))',
            'op'        => '=',
            'exp'       => '$field',
        ],
        'id',       //全部默认
        'name' => 'like',   //like 匹配
    ];

    private static $allowOpExpMapping = [
        '~',
        '>',
        '>=',
        '<',
        '<=',
        '!=',
        'in' => 'self::expToArray',
        '<>' => 'self::expToArray',
    ];

    private static $allowOps = [
        '~',
        '>',
        '>=',
        '<',
        '<=',
        '!=',
        'in',
        '<>',
    ];

    private static function expToArray($key, $field, $v)
    {
        if (is_array($v)) {
            return $v;
        } elseif (is_string($v)) {
            return explode(',', $v);
        } else {
            return null;
        }
    }


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

    public function autoFillWhere($filters)
    {
        $where = [];
        $search = json_decode(Container::getRequest()->get('search','{}'),true);

        foreach ($filters as $k => $filter) {
            $r = $this->completeFilter($k, $filter);
            if (!$r) {
                continue;
            }
            list($key, $filter) = $r;
            if (key_exists($key, $search)) {
                $value = $search[ $key ];
            } else {
                $value = $filter['default'];
            }

            // 过滤参数
            if ($filter['filter'] && function_exists($filter['filter'])) {
                $value = $filter['filter']($value);
            }

            // 判断是否满足条件
            $condition = $filter['condition'];
            if ('' === $condition) {
                $condition = $value;
            } elseif (function_exists($condition)) {      // 函数
                $condition = $condition($value);
            } elseif (preg_match('/^#exp\((.*?)\)$/', $condition, $cc)) { // exp 表达式
                $condition = str_replace('$field', $value, $cc[1]);
                unset($cc);
                eval("\$condition = $condition;");
            } elseif ($condition instanceof closure) {    // 闭包函数
                $condition = $filter['condition']($key, $filter['field'], $value);
            }
            if (!$condition) {    // 不满足搜索条件
                continue;
            }

            // 计算搜索值
            $exp = $value;
            if (function_exists($filter['exp'])) {
                $exp = $filter['exp']($value);
            } elseif (preg_match('/^#exp\((.*?)\)$/', $filter['exp'], $ee)) { // exp 表达式
                $exp = str_replace('$field', $exp, $ee[1]);
                unset($ee);
                eval("\$exp = $exp;");
            } elseif ($filter['exp'] instanceof closure) {    // 闭包函数
                $exp = $filter['exp']($key, $filter['field'], $exp);
            }
            // 赋值
            if ($filter['op']) {
                $where[ $filter['field'].'['.$filter['op'].']' ] = $exp;
            } else {
                $where[ $filter['field'] ] = $exp;
            }
        }

        //        $page = Container::getRequest()->get('page');
        //        $order = Container::getRequest()->get('order');

        return $where;
    }

    private function completeFilter($k, $v)
    {
        $key = '';
        $arr = [];
        if (is_numeric($k)) {
            // 索引数组
            if (is_string($v)) {
                $key          = $v;
                $arr['field'] = $v;
            } elseif (is_array($v)) {
                if (key_exists('field', $v)) {
                    $key = $v['field'];
                }
            }
        } else {
            $key = $k;
            // 关联数组
            if (is_string($v)) {
                if (in_array($v, self::$allowOpExpMapping)) {
                    $arr = [
                        'field' => $key,
                        'op'    => $v,
                    ];
                } elseif (key_exists($v, self::$allowOpExpMapping)) {
                    $arr = [
                        'field' => $key,
                        'op'    => $v,
                        'exp'   => self::$allowOpExpMapping[ $key ],
                    ];
                } elseif ('+int' === $v) {
                    $arr = [
                        'field'     => $key,
                        'condition' => function($key, $field, $v)
                        {
                            return preg_match('/^\d$/', $v);
                        },
                        'default'   => '-1',
                    ];
                } elseif ('number' === $v) {
                    $arr = [
                        'field'     => $key,
                        'condition' => function($key, $field, $v)
                        {
                            return is_numeric($v);
                        },
                    ];
                }
            } elseif (is_array($v)) {
                $arr = $v;
            } else {
                return false;
            }
        }
        if (empty($arr['field']))
            $arr['field'] = $key;
        if (!isset($arr['default']))
            $arr['default'] = '';
        if (!isset($arr['filter']))
            $arr['filter'] = '';
        if (!isset($arr['condition']))
            $arr['condition'] = '';
        if (!isset($arr['op']) || !in_array($arr['op'], self::$allowOps)) {
            $arr['op'] = '';
        }
        if (!isset($arr['exp']))
            $arr['exp'] = '';

        return [$key, $arr];
    }


    public function autoDataList($cols, $filters)
    {
        $where = $this->autoFillWhere($filters);
        $list  = $this->select($cols, $where);

        return $list;
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
<?php declare(strict_types=1);


namespace App\Ailab\Model;

use Swoft\Db\DB;
use Swoft\Log\Helper\CLog;


// 可以采用 单例模式

/**
 * 作为父类用于继承，从子类中获取表名
 * 完善数据获取方法
 * Class DBModel
 *
 */
class DBModel
{
    const DB_READ = '';
    const DB_WRITE = '';

    /**
     * 将 tp 的写法同步为 swoft 的写法
     * @param array $map
     * @return array
     */
    private static function syncSQL(array $map) :array{
        $params = [];
        foreach ($map as $item) {
            $temp = [];
            // if(count($item) === 2){
            //     $temp = [[$item[0], '=', $item[1]]];
            //     $params[] = $temp;
            //     continue;
            // }
            switch($item[1]){
                case '=':
                case '>':
                case '<':
                case '>=':
                case '<=':
                case '!=':
                case 'like':
                case 'not like':
                    $temp = ['key' => 'and', 'param' => [[$item[0], $item[1], $item[2]]]];
                    break;
                case 'between':
                case 'not between':
                case 'not in':
                case 'in':
                    $temp = ['key' => $item[1], 'param' => [$item[0], $item[2]]];
                    break;
                case 'not null':
                case 'null':
                    $temp = ['key' => $item[1], 'param' => $item[0]];
                    break;
                default:
                    break;
            }
            $params[] = $temp;
        }
        return $params;
    }

    /**
     * @param object $db
     * @param array $map
     * @param array $orderBy
     * @param array $groupBy
     * @return object
     */
    private static function getMethods(object $db, array $map = [], array $orderBy = [], array $groupBy = []) :object{
        if($map) {
            $params = self::syncSQL($map);
            foreach ($params as $param) {
                switch ($param['key']) {
                    case 'and':
                        $db = $db->where($param['param']); // 数组
                        break;
                    case 'or':
                        $db = $db->orWhere($param['param']); // 数组
                        break;
                    case 'between':
                        $db = $db->whereBetween($param['param'][0], $param['param'][1]);
                        break;
                    case 'not between':
                        $db = $db->whereNotBetween($param['param'][0], $param['param'][1]);
                        break;
                    case 'in':
                        $db = $db->whereIn($param['param'][0], $param['param'][1]);
                        break;
                    case 'not in':
                        $db = $db->whereNotIn($param['param'][0], $param['param'][1]);
                        break;
                    case 'null':
                        $db = $db->whereNull($param['param']); // 字符串
                        break;
                    case 'not_null':
                        $db = $db->whereNotNull($param['param']); // 字符串
                        break;
                    default:
                        break;
                }
            }
        }
        if($orderBy){
            foreach ($orderBy as $ork => $ord) {
                $db = $db->orderBy($ork, $ord);
            }
        }
        if($groupBy){
            foreach ($groupBy as $group) {
                foreach ($group as $gro) {
                    $db = $db->groupBy($gro);
                }
            }
        }
        return $db;
    }

    /**
     * 更快的查询全部的数据可以用cursor方法，底层采用 yield 实现。其中每个结果都是 Array
     *
     * @param
     * @return \Generator
     * @throws
     */
    public static function getCursorItem() :\Generator{
        return DB::table(static::$table)->cursor();
    }


    /**
     * @param array $where
     * @param string $select
     * @return array
     * @throws \ReflectionException
     * @throws \Swoft\Bean\Exception\ContainerException
     * @throws \Swoft\Db\Exception\DbException
     */
    public static function getItemsByWhere(array $where, string $select = '*') :array{
        $ret = DB::table(static::$table)->where($where)->select($select)->get()->toArray();
        $sql = DB::table(static::$table)->where($where)->toSql(); // 打印最后执行的sql
        CLog::info(json_encode($sql));
        return $ret;
    }

    /**
     * 从一个数据表中获取所有行
     * @param array $params
     * @param array $orderBy
     * @param array $groupBy
     * @param string $select
     * @return array
     * @throws \ReflectionException
     * @throws \Swoft\Bean\Exception\ContainerException
     * @throws \Swoft\Db\Exception\DbException
     */
    public static function getItems(array $params, array $orderBy = [], array $groupBy = [], string $select = '*')
:array{
        $db = DB::table(static::$table);
        // $db = DB::query(self::DB_READ)->from(static::$table); // 读写分离
        $db = self::getMethods($db, $params, $orderBy, $groupBy);
        return $db->select($select)->get()->toArray();
    }

    /**
     * 从数据表中获取单行
     * @param array $params
     * @param array $orderBy
     * @param array $groupBy
     * @param string $select
     * @return array
     * @throws \ReflectionException
     * @throws \Swoft\Bean\Exception\ContainerException
     * @throws \Swoft\Db\Exception\DbException
     */
    public static function getItem(array $params, array $orderBy = [], array $groupBy = [], string $select = '*') :array{
        $db = DB::table(static::$table);
        $db = self::getMethods($db, $params, $orderBy, $groupBy);
        return $db->select($select)->first();
    }

    /**
     * 获取一列的值
     * 可以指定键值
     * @param array $params
     * @param array $orderBy
     * @param array $groupBy
     * @param array $pluck
     * @return array
     * @throws \ReflectionException
     * @throws \Swoft\Bean\Exception\ContainerException
     * @throws \Swoft\Db\Exception\DbException
     */
    public static function getPluck(array $params, array $orderBy = [], array $groupBy = [], array $pluck = ['id'])
    :array{
        $db = DB::table(static::$table);
        $db = self::getMethods($db, $params, $orderBy, $groupBy);
        return $db->pluck(implode(',', $pluck))->toArray();
    }

    /**
     * 从数据表中获取单列--该方法将直接返回该字段的值
     * @param array $params
     * @param array $orderBy
     * @param array $groupBy
     * @param string $value
     * @return
     * @throws \ReflectionException
     * @throws \Swoft\Bean\Exception\ContainerException
     * @throws \Swoft\Db\Exception\DbException
     */
    public static function getValue(array $params, array $orderBy = [], array $groupBy = [], string $value = 'id'){
        $db = DB::table(static::$table);
        $db = self::getMethods($db, $params, $orderBy, $groupBy);
        return $db->value($value);
    }


    /**
     * @param array $params
     * @param int $page
     * @param int $size
     * @param array $orderBy
     * @param array $groupBy
     * @param string $select
     * @return array
     * @throws \ReflectionException
     * @throws \Swoft\Bean\Exception\ContainerException
     * @throws \Swoft\Db\Exception\DbException
     */
    public static function getPage(array $params, int $page = 1, int $size = 20, array $orderBy = [], array $groupBy
    = [], string $select = '*') :array{
        $db = DB::table(static::$table);
        $db = self::getMethods($db, $params, $orderBy, $groupBy);
        return $db->select($select)->forPage($page, $size)->get()->toArray();
    }


    // distinct


    // 插入
    /**
     * 返回的是影响的行数？
     * @param array $data 一维或二维数组
     * @return int
     * @throws \ReflectionException
     * @throws \Swoft\Bean\Exception\ContainerException
     * @throws \Swoft\Db\Exception\DbException
     */
    public static function insetItem(array $data) :int{
        return DB::table(static::$table)->insert($data);
    }
    /**
     * 返回的是自增ID
     * @param array $data 一维或二维数组
     * @return int
     * @throws \ReflectionException
     * @throws \Swoft\Bean\Exception\ContainerException
     * @throws \Swoft\Db\Exception\DbException
     */
    public static function insetIdItem(array $data) :int{
        return DB::table(static::$table)->insertGetId($data);
    }
    // 更新
    /**
     * @param array $params
     * @param array $data 要更新的字段
     * @return int
     * @throws \ReflectionException
     * @throws \Swoft\Bean\Exception\ContainerException
     * @throws \Swoft\Db\Exception\DbException
     */
    public static function updateItem(array $params, array $data) :int{
        $db = DB::table(static::$table);
        $db = self::getMethods($db, $params);
        return $db->update($data);
    }
    // 删除
    /**
     * @param array $params
     * @param array $data 要更新的字段
     * @return int
     * @throws \ReflectionException
     * @throws \Swoft\Bean\Exception\ContainerException
     * @throws \Swoft\Db\Exception\DbException
     */
    public static function deleteItem(array $params) :int{
        $db = DB::table(static::$table);
        $db = self::getMethods($db, $params);
        return $db->delete();
    }

    // 聚合函数类
    /**
     * count
     * @param array $params
     * @return int
     * @throws \ReflectionException
     * @throws \Swoft\Bean\Exception\ContainerException
     * @throws \Swoft\Db\Exception\DbException
     */
    public static function getCount(array $params, string $field = 'id') :int{
        $db = DB::table(static::$table);
        $db = self::getMethods($db, $params);
        return $db->count($field);
    }
    /**
     * max
     * @param array $params
     * @return float|int
     * @throws \ReflectionException
     * @throws \Swoft\Bean\Exception\ContainerException
     * @throws \Swoft\Db\Exception\DbException
     */
    public static function getMax(array $params){
        $db = DB::table(static::$table);
        $db = self::getMethods($db, $params);
        return $db->max();
    }
    /**
     * min
     * @param array $params
     * @return float|int
     * @throws \ReflectionException
     * @throws \Swoft\Bean\Exception\ContainerException
     * @throws \Swoft\Db\Exception\DbException
     */
    public static function getMin(array $params){
        $db = DB::table(static::$table);
        $db = self::getMethods($db, $params);
        return $db->min();
    }
    /**
     * sum
     * @param array $params
     * @return float|int
     * @throws \ReflectionException
     * @throws \Swoft\Bean\Exception\ContainerException
     * @throws \Swoft\Db\Exception\DbException
     */
    public static function getSum(array $params, string $field){
        $db = DB::table(static::$table);
        $db = self::getMethods($db, $params);
        return $db->sum($field);
    }
    /**
     * avg
     * @param array $params
     * @param string $field
     * @return float|int
     * @throws \ReflectionException
     * @throws \Swoft\Bean\Exception\ContainerException
     * @throws \Swoft\Db\Exception\DbException
     */
    public static function getAvg(array $params, string $field){
        $db = DB::table(static::$table);
        $db = self::getMethods($db, $params);
        return $db->avg($field);
    }

    // 原生操作 
    /**
     * 返回数组
     * @param string $sql
     * @param array $params
     * @return array
     * @throws \ReflectionException
     * @throws \Swoft\Bean\Exception\ContainerException
     * @throws \Swoft\Db\Exception\DbException
     */
    public static function exexSelect(string $sql, array $params = []) :array{
        return DB::select($sql, $params); 
    }
    /**
     * 
     * @param string $sql
     * @param array $params
     * @return int
     * @throws \ReflectionException
     * @throws \Swoft\Bean\Exception\ContainerException
     * @throws \Swoft\Db\Exception\DbException
     */
    public static function exexInsert(string $sql, array $params = []) :int{
        return DB::insert($sql, $params); 
    }
    /**
     * 返回受该语句影响的行数
     * @param string $sql
     * @param array $params
     * @return int
     * @throws \ReflectionException
     * @throws \Swoft\Bean\Exception\ContainerException
     * @throws \Swoft\Db\Exception\DbException
     */
    public static function exexUpdate(string $sql, array $params = []) :int{
        return DB::update($sql, $params); 
    }
    /**
     * 返回受该语句影响的行数
     * @param string $sql
     * @param array $params
     * @return int
     * @throws \ReflectionException
     * @throws \Swoft\Bean\Exception\ContainerException
     * @throws \Swoft\Db\Exception\DbException
     */
    public static function exexDelete(string $sql, array $params = []) :int{
        return DB::delete($sql, $params); 
    }

}
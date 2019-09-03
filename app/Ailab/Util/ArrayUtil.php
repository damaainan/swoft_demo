<?php declare(strict_types=1);


namespace App\Ailab\Util;

use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Bean\Annotation\Mapping\Inject;
use Swoft\Bean\BeanFactory;

/**
 * Class ArrayUtil
 *
 * @since 2.0
 *
 * @Bean(scope=Bean::PROTOTYPE, name="arrayUtil")
 */
class ArrayUtil
{

    /**
     * @Inject("objectUtil")
     *
     * @var ObjectUtil
     */
//    private $objectUtil;

    /**
     * 根据指定键名，获取所在元素值，当做新数组的键名，返回新数组
     *
     * @param array $inArray 二维数组
     * @param string $keyname 指定键名
     * @param string $keyvalue 希望获取的字段名称
     * @return array
     * @throws
     */
    // 在php中出现Using $this when not in object context的原因是在静态方法中使用$this或者直接调用非静态的方法。
    public static function buildArrayByKeyValue(array $inArray, string $keyname, string $keyvalue ) :array {
        $ret = array();
        if ( !$inArray ) {
            return $ret;
        }
        // 工具类之间互相调用
        // $objectUtil = BeanFactory::getBean('objectUtil');
        foreach ( $inArray as $val ) {
            if ( is_array( $val ) ) {
                if ( isset( $val[$keyname] ) ) {
                    // $ret[$val[$keyname]] = $val[$keyvalue];
                    $ret[$val[$keyname]] = BeanFactory::getBean('objectUtil')::maybeGetArray($val, $keyvalue);
                }
            } elseif ( is_object( $val ) ) {
                if ( isset( $val->$keyname ) ) {
                    // $ret[$val->$keyname] = $val[$keyvalue];
                    $ret[$val[$keyname]] = BeanFactory::getBean('objectUtil')::maybeGetObject($val, $keyvalue);
                }
            }
        }
        return $ret;
    }

    /**
     * Returns the first item that match the key values of all properties of the object <code>keyValues</code>.
     *
     * @param array $obj inArray: Array to search for an element with every key value in the object
     * <code>keyValues</code>.
     * @param array keyValues: An object with key value pairs.
     * @return array Returns the first matched item; otherwise <code>null</code>.
     * @example <code>
     *          var people:Array = new Array({name: "Aaron", sex: "Male", hair: "Brown"}, {name: "Linda", sex: "Female", hair: "Blonde"}, {name: "Katie", sex: "Female", hair: "Brown"}, {name: "Nikki", sex: "Female", hair: "Blonde"});
     *          var person:Object = ArrayUtil.getItemByKeys(people, {sex: "Female", hair: "Brown"});
     *          trace(person.name); // Traces "Katie"
     *          </code>
     */
    public static function getItemByKeys( $obj, Array $keyValues ) :array{
        $ret = array();
        if ( !$obj ) {
            return $ret;
        }
        $i = -1;
        // $hasKeys = false;
        $len = count( $obj );
        while ( ++$i < $len ) {
            $val = $obj[$i];
            $hasKeys = true;
            foreach ( $keyValues as $k => $v ) {
                if ( is_array( $val ) ) {
                    if ( !( isset( $val[$k] ) && $val[$k] === $v ) ) {
                        $hasKeys = false;
                    }
                } elseif ( is_object( $val ) ) {
                    if ( !( isset( $val->$k ) && $val->$k === $v ) ) {
                        $hasKeys = false;
                    }
                }
            }
            if ( $hasKeys ) {
                return $val;
            }
        }

        return $ret;
    }

    /**
     * Returns all items that match the key values of all properties of the object <code>keyValues</code>.
     *
     * @param array $obj: Array to search for elements with every key value in the object <code>keyValues</code>.
     * @param array $keyValues: An object with key value pairs.
     * @return array Returns all the matched items.
     * @example <code>
     *          var people:Array = new Array({name: "Aaron", sex: "Male", hair: "Brown"}, {name: "Linda", sex: "Female", hair: "Blonde"}, {name: "Katie", sex: "Female", hair: "Brown"}, {name: "Nikki", sex: "Female", hair: "Blonde"});
     *          var blondeFemales:Array = ArrayUtil::getItemsByKeys(people, {sex: "Female", hair: "Blonde"});
     *          for each (var p:Object in blondeFemales) {
     *          trace(p.name);
     *          }
     *          </code>
     */
    public static function getItemsByKeys( $obj, Array $keyValues ) :array{
        $ret = array();
        if ( !$obj ) {
            return $ret;
        }
        $i = -1;
        // $hasKeys = false;
        $len = count( $obj );
        while ( ++$i < $len ) {
            $val = $obj[$i];
            $hasKeys = true;
            foreach ( $keyValues as $k => $v ) {
                if ( is_array( $val ) ) {
                    if ( !( isset( $val[$k] ) && $val[$k] === $v ) ) {
                        $hasKeys = false;
                    }
                } elseif ( is_object( $val ) ) {
                    if ( !( isset( $val->$k ) && $val->$k === $v ) ) {
                        $hasKeys = false;
                    }
                }
            }
            if ( $hasKeys ) {
                $ret[] = $val;
            }
        }

        return $ret;
    }

    /**
     * Returns the first item that match a key value of any property of the object <code>keyValues</code>.
     *
     * @param array inArray: Array to search for an element with any key value in the object <code>keyValues</code>.
     * @param array keyValues: An object with key value pairs.
     * @return array Returns the first matched item; otherwise <code>null</code>.
     * @example <code>
     *          var people:Array = new Array({name: "Aaron", sex: "Male", hair: "Brown"}, {name: "Linda", sex: "Female", hair: "Blonde"}, {name: "Katie", sex: "Female", hair: "Brown"}, {name: "Nikki", sex: "Female", hair: "Blonde"});
     *          var person:Object = ArrayUtil.getItemByAnyKey(people, {sex: "Female", hair: "Brown"});
     *          trace(person.name); // Traces "Aaron"
     *          </code>
     */
    public static function getItemByAnyKey( $inArray, Array $keyValues ) :array{
        $i = -1;
        // $hasKeys = false;
        $len = count( $inArray );

        while ( ++$i < $len ) {
            $val = $inArray[$i];
            foreach ( $keyValues as $k => $v ) {

                if ( is_array( $val ) ) {
                    if ( isset( $val[$k] ) && $val[$k] === $v ) {
                        return $val;
                        break;
                    }
                } elseif ( is_object( $val ) ) {
                    if ( isset( $val->$k ) && $val->$k === $v ) {
                        return $val;
                        break;
                    }
                }
            }
        }
        return array();
    }

    /**
     * Returns all items that match a key value of any property of the object <code>keyValues</code>.
     *
     * @param $inArray: Array to search for elements with any key value in the object <code>keyValues</code>.
     * @param $keyValues: An object with key value pairs.
     * @return array Returns all the matched items.
     * @example <code>
     *          var people:Array = new Array({name: "Aaron", sex: "Male", hair: "Brown"}, {name: "Linda", sex: "Female", hair: "Blonde"}, {name: "Katie", sex: "Female", hair: "Brown"}, {name: "Nikki", sex: "Female", hair: "Blonde"});
     *          var brownOrFemales:Array = ArrayUtil::getItemsByAnyKey(people, {sex: "Female", hair: "Brown"});
     *          for each (var p:Object in brownOrFemales) {
     *          trace(p.name);
     *          }
     *          </code>
     */
    public static function getItemsByAnyKey( $inArray, Array $keyValues ) :array{
        $ret = array();
        $i = -1;
        // $hasKeys = false;
        $len = count( $inArray );

        while ( ++$i < $len ) {
            $val = $inArray[$i];
            foreach ( $keyValues as $k => $v ) {

                if ( is_array( $val ) ) {
                    if ( isset( $val[$k] ) && $val[$k] === $v ) {
                        $ret[] = $val;
                        break;
                    }
                } elseif ( is_object( $val ) ) {
                    if ( isset( $val->$k ) && $val->$k === $v ) {
                        $ret[] = $val;
                        break;
                    }
                }
            }
        }
        return $ret;
    }

    /**
     * Returns the first element that matches <code>match</code> for the property <code>key</code>.
     *
     * @param array $inArray: Array to search for an element with a <code>key</code> that matches <code>match</code>.
     * @param string key: Name of the property to match.
     * @param string match: Value to match against.
     * @return array Returns matched item; otherwise <code>null</code>.
     */
    public static function getItemByKey( $inArray, $key, $match ) :array{
        $ret = array();
        if ( !$inArray ) {
            return $ret;
        }
        foreach ( $inArray as $val ) {
            if ( is_array( $val ) ) {

                if ( isset( $val[$key] ) && $val[$key] === $match ) {
                    return $val;
                }
            } elseif ( is_object( $val ) ) {
                if ( isset( $val->$key ) && $val->$key === $match ) {
                    return $val;
                }
            }
        }
        return $ret;
    }

    /**
     * Returns every element that matches <code>match</code> for the property <code>key</code>.
     *
     * @param array inArray: Array to search for object with <code>key</code> that matches <code>match</code>.
     * @param string key: Name of the property to match.
     * @param string match: Value to match against.
     * @return array Returns all the matched items.
     */
    public static function getItemsByKey( $inArray, $key, $match ) :array{
        $ret = array();
        if ( !$inArray ) {
            return $ret;
        }
        foreach ( $inArray as $val ) {
            if ( is_array( $val ) ) {

                if ( isset( $val[$key] ) && $val[$key] === $match ) {
                    $ret[] = $val;
                }
            } elseif ( is_object( $val ) ) {
                if ( isset( $val->$key ) && $val->$key === $match ) {
                    $ret[] = $val;
                }
            }
        }
        return $ret;
    }

    public static function getItemsByOwnKey( $inArray, $key ) :array{
        $ret = array();
        if ( !$inArray ) {
            return $ret;
        }
        foreach ( $inArray as $val ) {
            if ( is_array( $val ) ) {

                if ( isset( $val[$key] ) ) {
                    $ret[] = $val;
                }
            } elseif ( is_object( $val ) ) {
                if ( isset( $val->$key ) ) {
                    $ret[] = $val;
                }
            }
        }
        return $ret;
    }

    /**
     * Returns the value of the specified property for every element where the key is present.
     *
     * @param array $inArray: Array to get the values from.
     * @param string $keyname: Name of the property to retrieve the value of.
     * @return boolean|array all the present key values.
     */
    public static function getValuesByKey( $inArray, $keyname ) :array{
        $ret = array();
        if ( !$inArray ) {
            return $ret;
        }
        foreach ( $inArray as $val ) {
            if ( is_array( $val ) ) {

                if ( isset( $val[$keyname] ) ) {
                    $ret[] = $val[$keyname];
                }
            } elseif ( is_object( $val ) ) {
                if ( isset( $val->$keyname ) ) {
                    $ret[] = $val->$keyname;
                }
            }
        }
        return $ret;
    }

    public static function getNumberValuesByKey( $inArray, $keyname ) :array{
        $ret = array();
        if ( !$inArray ) {
            return $ret;
        }
        foreach ( $inArray as $val ) {
            if ( is_array( $val ) ) {

                if ( isset( $val[$keyname] ) ) {
                    $ret[] = $val[$keyname] + 0;
                }
            } elseif ( is_object( $val ) ) {
                if ( isset( $val->$keyname ) ) {
                    $ret[] = $val->$keyname + 0;
                }
            }
        }
        return $ret;
    }

    /**
     * 根据指定键名，获取所在元素值，当做新数组的键名，返回新数组
     *
     * @param array $inArray 二维数组
     * @param string $keyname 指定键名
     * @return array
     */
    public static function buildArrayByKey( $inArray, $keyname ) :array{
        $ret = array();
        if ( !$inArray ) {
            return $ret;
        }
        foreach ( $inArray as $val ) {
            if ( is_array( $val ) ) {

                if ( isset( $val[$keyname] ) ) {
                    $ret[$val[$keyname]] = $val;
                }
            } elseif ( is_object( $val ) ) {
                if ( isset( $val->$keyname ) ) {
                    $ret[$val->$keyname] = $val;
                }
            }
        }
        return $ret;
    }
    /**
     * 根据指定键名，获取所在元素值，当做新数组的键名，返回新数组
     *
     * @param array $inArray 二维数组
     * @param string $keyname 指定键名
     * @param string $keyvalue 希望获取的字段名称
     * @throws
     * @return array
     */
    public static function buildArrayValueByKey( $inArray, $keyname, $keyvalue ) :array{
        $ret = array();
        if ( !$inArray ) {
            return $ret;
        }
        foreach ( $inArray as $val ) {
            if ( is_array( $val ) ) {
                if ( isset( $val[$keyname] ) ) {
                    $ret[$val[$keyname]][] = BeanFactory::getBean('objectUtil')::maybeGetObject($val, $keyvalue);
                }
            } elseif ( is_object( $val ) ) {
                if ( isset( $val->$keyname ) ) {
                    $ret[$val->$keyname][] = BeanFactory::getBean('objectUtil')::maybeGetObject($val, $keyvalue);
                }
            }
        }
        return $ret;
    }
    public static function buildArrayValuesByKey( $inArray, $keyname ) :array{
        $ret = array();
        if ( !$inArray ) {
            return $ret;
        }
        foreach ( $inArray as $val ) {
            if ( is_array( $val ) ) {
                if ( isset( $val[$keyname] ) ) {
                    $ret[$val[$keyname]][] = $val;
                }
            } elseif ( is_object( $val ) ) {
                if ( isset( $val->$keyname ) ) {
                    $ret[$val->$keyname][] = $val;
                }
            }
        }
        return $ret;
    }


}
<?php declare(strict_types=1);


namespace App\Ailab\Util;

use Swoft\Bean\Annotation\Mapping\Bean;

/**
 * Class ObjectUtil
 *
 * @since 2.0
 *
 * @Bean(scope=Bean::PROTOTYPE, name="objectUtil")
 */
class ObjectUtil
{
    /**
     *
     * @var int 递归的最大层次
     */
    public const RECURSION_LIMIT = 3;

	/**
	 * maybeGetObject description
	 * @param  object $obj     
	 * @param  string $name    
	 * @param  string|null $default 
	 * @return object          
	 */
	public static function maybeGetObject(object $obj, string $name, string $default = null ) :object {
        return self::maybeGetValue( $obj, $name, $default );
    }


    /**
     * @param $obj
     * @param $name
     * @param array $default
     * @return array|object
     */
    public static function maybeGetArray($obj, $name, $default = array() ) :array {
        $ret = self::maybeGetValue( $obj, $name, $default );
        if ( is_array( $ret ) ) {
            return $ret;
        }
        return $default;
    }

    /**
     * maybeGetValue description
     * @param  object $obj     
     * @param  string $name    
     * @param  string|null $default 
     * @return mixed
     */
    public static function maybeGetValue($obj, $name, $default = null )  {
        if ( $obj && is_array( $obj ) && isset( $obj[$name] ) ) {
            return $obj[$name];
        }
        if ( $obj && is_object( $obj ) && isset( $obj->$name ) ) {
            return $obj->$name;
        }
        return $default;
    }

    public static function maybeGetString( $obj, $name, $default = '' ) :string{
        $ret = self::maybeGetValue( $obj, $name, $default );
        return $ret . '';
    }

    public static function maybeGetInt( $obj, $name, $default = 0 ) :int{
        $ret = self::maybeGetValue( $obj, $name, $default );
        return (int)$ret;
    }

    public static function maybeGetNumber( $obj, $name, $default = 0 ) {
        $ret = self::maybeGetValue( $obj, $name, $default );
        if(!$ret){
            $ret = $default;
        }
        return $ret + 0;
    }


    public static function maybeGetBoolean( $obj, $name, $default = false ) :bool{
        $ret = self::maybeGetValue( $obj, $name, $default );
        return (bool)$ret;
    }


    /**
     * 将对象递归返回为数组格式
     *
     * @param object $object
     * @param int $recursion_level 当前递归层数，根节点为0
     * @param int $max_level 最大递归层数，从根节点算起，包含几层
     * @return boolean|array
     */
    public static function object2Array( $object, $recursion_level = 0, $max_level = 1 ) :array{
        if ( !$object ) {
            return false;
        }
        if ( is_object( $object ) ) {
            $vars = get_object_vars( $object );
        } elseif ( is_array( $object ) ) {
            $vars = $object;
        } else {
            return false;
        }
        if ( $recursion_level >= self::RECURSION_LIMIT ) {
            return false;
        }
        if ( $recursion_level >= $max_level ) {
            return false;
        }
        $recursion_level++;

        foreach ( $vars as $key => $val ) {
            $vars[$key] = self::object2Array( $val, $recursion_level, $max_level );
        }
        return $vars;
    }

}
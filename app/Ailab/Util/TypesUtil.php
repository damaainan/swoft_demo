<?php declare(strict_types=1);


namespace App\Ailab\Util;

use phpDocumentor\Reflection\Types\Integer;
use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Context\Context;
use UnexpectedValueException;

/**
 * Class TypesUtil
 *
 * @since 2.0
 *
 * @Bean(scope=Bean::PROTOTYPE, name="typesUtil")
 */
// name 的值必须为双引号
class TypesUtil
{

    public const TYPE_NONE = 0;
    public const TYPE_INT = 1;
    public const TYPE_FLOAT = 2;
    public const TYPE_STRING = 3;
    public const TYPE_BOOL = 4;
    public const TYPE_ARRAY = 5;
    public const TYPE_TIMESTAMP = 3;

    /**
     * Retrieves an value from the $_GET and $_POST variables, in that order.  If it isnt found, $default is used.
     * This version of safeget is typesafe, it requires a type enum and guarantees that the specified type will be returned.
     *
     * @param string	$name		Variable name to retrieve
     * @param integer	$type		Type of the variable to retrieve, must be one of the MML_TYPE_* enums.
     * @param mixed		$default	Default value to assign if the variable isnt found in the post and get vars.
     *
     * @return mixed	Returns the value retreived from GET, POST, or the default value.
     * @throws
     */
    public static function safeGetTyped($name, $type, $default=NULL) {
        $result = NULL;

        switch($type) {
            case self::TYPE_STRING:
                $result = self::safeGetString($name, $default);
                break;
            case self::TYPE_INT:
                $result = self::safeGetInt($name, $default);
                break;
            case self::TYPE_FLOAT:
                $result = self::safeGetFloat($name, $default);
                break;
            case self::TYPE_BOOL:
                $result = self::safeGetBool($name, $default);
                break;
            case self::TYPE_ARRAY:
                $result = self::safeGetArray($name, $default);
                break;
            default:
                throw new UnexpectedValueException('Invalid type passed to safeGetTyped!');
                break;
        }

        return $result;
    }

    /**
     * Checks whether a request parameter exists
     * @param	string	$name		The parameter to check for
     * @return	boolean				Whether or not the parameter exists
     */
    public static function safeGetExists($name) :bool{
        if(isset($_GET[$name])) {
            return true;
        }
        if(isset($_POST[$name])) {
            return true;
        }
        return false;
    }

    /**
     * Retrieves variables from the $_GET and $_POST variables, in that order.
     * This function is typesafe and verifies that all arguments are typed otherwise a FatalError is thrown.
     *
     * @param array		$fields		Fields to retreive
     *
     * @return array	Returns the array of values retreived from $_GET/$_POST.
     * @throws
     */
    public static function safeGetTypedArray($fields) :array{
        $result = array();

        foreach($fields as $key => $fieldType) {
            if(is_array($fieldType) && count($fieldType)>=1) {
                $result[$key] = self::safeGetTyped($key, $fieldType[0], $fieldType[1]);
            } else {
                throw new UnexpectedValueException('Invalid type passed to safeGetTypedArray!');
            }
        }

        return $result;
    }


    /**
     * Retrieves an integer value from the $_GET and $_POST variables, in that order.  If it isnt found, $default is used.
     *
     * @param string	$name		Variable name to retrieve
     * @param mixed		$default	Default value to assign if the variable isnt found in the post and get vars.
     *
     * @return integer|null	Returns the value retreived from GET, POST, or the default value.
     */
    public static function safeGetInt($name, $default=NULL) :?int{
        $request = Context::mustGet()->getRequest();
        $result = $request->input($name, $default);
        if(NULL === $default){
            return NULL;
        }
        return (int)$result;
    }

    /**
     * Retrieves a float value from the $_GET and $_POST variables, in that order.  If it isnt found, $default is used.
     *
     * @param string	$name		Variable name to retrieve
     * @param mixed		$default	Default value to assign if the variable isnt found in the post and get vars.
     *
     * @return float|null	Returns the value retreived from GET, POST, or the default value.
     */
    public static function safeGetFloat($name, $default=NULL) :?float{
        $request = Context::mustGet()->getRequest();
        $result = $request->input($name, $default);
        if(NULL === $default){
            return NULL;
        }
        return (float)$result;
    }

    /**
     * Retrieves a string value from the $_GET and $_POST variables, in that order.  If it isnt found, $default is used.
     *
     * @param string	$name		Variable name to retrieve
     * @param mixed		$default	Default value to assign if the variable isnt found in the post and get vars.
     *
     * @return string|null	Returns the value retreived from GET, POST, or the default value.
     */
    public static function safeGetString($name, $default=NULL) :?string{
        $request = Context::mustGet()->getRequest();
        $result = $request->input($name, $default);
        if(NULL === $default){
            return NULL;
        }
        return (string)$result;
    }


    public static function safeGetJson() {
        return file_get_contents('php://input');
    }

    /**
     * Retrieves a boolean value from the $_GET and $_POST variables, in that order.  If it isnt found, $default is used.
     *
     * @param string	$name		Variable name to retrieve
     * @param mixed		$default	Default value to assign if the variable isnt found in the post and get vars.
     *
     * @return bool	Returns the value retreived from GET, POST, or the default value.
     */
    public static function safeGetBool($name, $default=NULL) :bool{
        $request = Context::mustGet()->getRequest();
        $result = $request->input($name, $default);

        return (bool)$result;
    }

    /**
     * Retrieves a array value from the $_GET and $_POST variables, in that order.  If it isnt found, $default is used.
     *
     * @param string	$name		Variable name to retrieve
     * @param mixed		$default	Default value to assign if the variable isnt found in the post and get vars.
     *
     * @return array|null	Returns the value retreived from GET, POST, or the default value.
     */
    public static function safeGetArray($name, $default=NULL) :?array{
        $result = $default;

        if(isset($_GET[$name]) && is_array($_GET[$name])) {
            $result = $_GET[$name];
        }
        else if(isset($_POST[$name]) && is_array($_POST[$name])) {
            $result = $_POST[$name];
        }

        if($result !== null && is_array($result)) {
            array_walk($result,array('TypesUtil','strip_tags_array_walk'));
        }

        return $result;
    }

    public static function strip_tags_array_walk( &$value ) :void{
        $value = strip_tags( $value );
    }
}
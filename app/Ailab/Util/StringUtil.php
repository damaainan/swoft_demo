<?php declare(strict_types=1);


namespace App\Ailab\Util;

use phpDocumentor\Reflection\Types\Integer;
use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Context\Context;
use UnexpectedValueException;

/**
 * Class StringUtil
 *
 * @since 2.0
 *
 * @Bean(scope=Bean::PROTOTYPE, name="stringUtil")
 */
// name 的值必须为双引号
class StringUtil
{
	/**
     * 按分隔符分割字符串返回结果数组
     * @param  string $string
     * @param  string $delimeter
     * @return array  $arr
     */
    public static function getArrByDelimeter($string, string $delimeter = ",")
    {
        if(!$string){
            return false;
        }
        $arr = explode($delimeter, $string);
        $resu = [];
        foreach ($arr as $val) {
            $val = str_replace(" ", "", $val);
            $val = str_replace("\r\n", "", $val);
            if($val === ''){
                continue;
            }
            $resu[] = $val;
        }
        return $resu;
    }
}
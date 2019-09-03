<?php declare(strict_types=1);


namespace App\Ailab\Util;

use phpDocumentor\Reflection\Types\Integer;
use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Context\Context;
use UnexpectedValueException;

/**
 * Class MathUtil
 *
 * @since 2.0
 *
 * @Bean(scope=Bean::PROTOTYPE, name="mathUtil")
 */
class MathUtil
{
	/**
	 * 分解整数为二进制，获取指数部分
	 * @param  int    $num 
	 * @return array      
	 */
	public static function getBinArray(int $num): array{
		$data = [];
		$ret = log($num, 2);
		$data[] = floor($ret);
		while($ret > 1){
			$num = $num - pow(2, floor($ret));
			if($num < 2){
				break;
			}
			$ret = log($num, 2);
			$data[] = floor($ret);
		}
		return $data;
	}
}

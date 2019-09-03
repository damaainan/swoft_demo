<?php declare(strict_types=1);


namespace App\Ailab\Util;

use Swlib\SaberGM;
use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Bean\Annotation\Mapping\Inject;
use Swoft\Bean\BeanFactory;

/**
 * Class ArrayUtil
 *
 * @since 2.0
 *
 * @Bean(scope=Bean::PROTOTYPE, name="curlUtil")
 */
class CurlUtil
{
	// https://github.com/swlib/saber

    public static function get(String $url, array $data){
        $ret = SaberGM::get($url, $data);
        // 判断状态码等
        return $ret;
    }

    public static function post(String $url, array $data){
        $ret = SaberGM::post($url, $data);
        // 判断状态码等
        return $ret;
    }
}
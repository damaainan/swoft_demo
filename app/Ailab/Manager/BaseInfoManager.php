<?php declare(strict_types=1);

namespace App\Ailab\Manager;

use App\Ailab\Model\Dao\CqInfoDao;
use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Bean\Annotation\Mapping\Inject;
use Swoft\Bean\BeanFactory; // 需要引入

// 可以采用 单例模式

/**
 * Class BaseInfoManager
 *
 * @since 2.0
 *
 * @Bean(scope=Bean::REQUEST, name="baseInfoManager")
 */
class BaseInfoManager
{
	/**
     * @Inject("constantUtil")
     *
     * @var ConstantUtil
     */
	private $constantUtil;

	public static function getEmotionInfo(int $class_id, int $start){
		// Log::record('情绪曲线接口请求');
		$param = [];
		$param['classId'] = $class_id;
		$param['time'] = $start;
		$key = "test";
		$tag = "test";

		$res = self::requestApi($key, $tag, $param, $url);

		if(!$res || (int)$res['code'] !== 0 || !$res['data']){
			return [];
		}
		return $res['data'];
	}

	//	获取语音文字识别接口信息 
	//	
	//	
	public static function getAudioTextInfo($param) :array{
		// Log::record('语音信息接口请求');
		$curlUtil = BeanFactory::getBean('curlUtil');
		$objectUtil = BeanFactory::getBean('objectUtil');
		$ret = $curlUtil::get($url, $param);
			
		// echo $ret;die();
		$ret = json_encode($ret);
		$res = json_decode($ret, true);

		if(isset($res['http_code']) && (int)$res['http_code'] >= 400){
			return [];
		}

		if(!$ret || !isset($res['code']) || (int)$res['code'] !== 0 || !$res['data']){
			return [];
		}
		$result = [];
		$result['audios'] = [];
		$result['audios']['student'] = [];
		$result['audios']['teacher'] = [];
		// halt($res);
		foreach ($res['data']['teacherUtters'] as $val) {
			$temp = [];
			$temp['start'] = round($val['from'] / 1000);
			$temp['end'] = round($val['to'] / 1000);
			$temp['text'] = $objectUtil::maybeGetString($val, 'text');
			$result['audios']['teacher'][] = $temp;
		}
		foreach ($res['data']['studentUtters'] as $value) {
			$temp = [];
			$temp['start'] = round($value['from'] / 1000);
			$temp['end'] = round($value['to'] / 1000);
			$temp['text'] = $objectUtil::maybeGetString($value, 'text');
			$result['audios']['student'][] = $temp;
		}
		return $result;
	}

	//	获取语音指标计算接口信息 
	//	
	//	
	public static function getAudioCalcInfo($param) :array{
		$objectUtil = BeanFactory::getBean('objectUtil');
		$curlUtil = BeanFactory::getBean('curlUtil');
		$timeUtil = BeanFactory::getBean('timeUtil');

		$ret = $curlUtil::get($url, $param);
			
		$ret = json_encode($ret);
		$res = json_decode($ret, true);

		if(isset($res['http_code']) && (int)$res['http_code'] >= 400){
			return [];
		}

		if(!$ret || !isset($res['code']) || (int)$res['code'] !== 0 || !$res['data']){
			return [];
		}
		$result = [];
		$result['problem'] = [];
		$teacher_duration = round( $objectUtil::maybeGetInt($res['data'], 'voiceDurationTea') / 1000);
		$student_duration = round( $objectUtil::maybeGetInt($res['data'], 'voiceDurationStu') / 1000);
		$tea_audio_speed = $objectUtil::maybeGetInt($res['data'], 'avgWordSpeedTea');
		$source = $objectUtil::maybeGetInt($res['data'], 'source', 1); //数据来源 1:自研, 2:阿里
		// 获取 level 
		$level = $param['level'];
		if(!$level || 1 === $source){
			$health['teacher_duration_desc'] = '正常';
			$health['student_duration_desc'] = '正常';
			$health['tea_audio_speed_desc'] = '正常';
		}else{
			$health['teacher_duration_desc'] = '正常';
			if($teacher_duration > self::$constantUtil->$audio_format_value['teacher_duration'][$level]['end']){
				$health['teacher_duration_desc'] = '过长';
			}else if($teacher_duration < self::$constantUtil->$audio_format_value['teacher_duration'][$level]['start']){
				$health['teacher_duration_desc'] = '过短';
			}
			$health['student_duration_desc'] = '正常';
			if($student_duration > self::$constantUtil->$audio_format_value['student_duration'][$level]['end']){
				$health['student_duration_desc'] = '过长';
			}else if($student_duration < self::$constantUtil->$audio_format_value['student_duration'][$level]['start']){
				$health['student_duration_desc'] = '过短';
			}
			$health['tea_audio_speed_desc'] = '正常';
			if($tea_audio_speed > self::$constantUtil->$audio_format_value['tea_audio_speed'][$level]['end']){
				$health['tea_audio_speed_desc'] = '过快';
			}else if($tea_audio_speed < self::$constantUtil->$audio_format_value['tea_audio_speed'][$level]['start']){
				$health['tea_audio_speed_desc'] = '过慢';
			}
		}
		$health['teacher_duration'] = $timeUtil::getMinutes($teacher_duration);
		$health['student_duration'] = $timeUtil::getMinutes($student_duration);
		$health['tea_audio_speed'] = $tea_audio_speed . ' words/min';
		$result['health'] = $health;

		$result['problem']['silent_duration'] = round($objectUtil::maybeGetInt($res['data'], 'totalSilenceDuration') / 1000);
		$result['problem']['silent'] = [];
		if($res['data']['silenceTime']){
			foreach ($res['data']['silenceTime'] as $value) {
				$temp = [];
				$temp['start'] = round($objectUtil::maybeGetInt($value, 'from') / 1000);
				$temp['end'] = round($objectUtil::maybeGetInt($value, 'to') / 1000);
				$result['problem']['silent'][] = $temp;
			}
		}

		return $result;
	}


	private static function requestApi(string $key, string $tag, array $param, string $url, string $method = 'get', int $cache = 1, int $test = 1) :array{
		$curlUtil = BeanFactory::getBean('curlUtil');

		// 是否使用缓存
		// if($cache){
		// 	$ret = CacheUtil::getValueByKey($key);
	 //        if($ret){
	 //            $api_end = TimeUtil::getServerMicroTime();
	 //            // LogUtil::record($tag, $api_start, $api_end, $param, $ret, 1);
	 //            $ret = json_decode($ret, true);
	 //            return $ret;
	 //        }
		// }

		// 是否使用测试数据
        if($test){
			$ret = [];
		}else{
			if($method === 'postjson'){
                $ret = $curlUtil::postJson($url, $param);
            }else if($method === 'post'){
                $ret = $curlUtil::post($url, $param);
            }else{
				$ret = $curlUtil::get($url, $param);
			}
		}
		$ret = json_encode($ret);
		$res = json_decode($ret, true);

		if(isset($res['http_code']) && (int)$res['http_code'] >= 400){
			return [];
		}

		if(!$ret || !$res){
			return [];
		}
		return $res;
	}
}
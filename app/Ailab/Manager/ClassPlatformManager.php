<?php declare(strict_types=1);

namespace App\Ailab\Manager;

use App\Ailab\Model\Dao\{OnlineClassBackDao, CpLessonInfoDao};
use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Bean\Annotation\Mapping\Inject;
use Swoft\Bean\BeanFactory; // 需要引入
use Swoft\Co;
use Swoft\Log\Helper\CLog;

// 可以采用 单例模式

/**
 * Class ClassPlatformManager
 *
 * @since 2.0
 *
 * @Bean(scope=Bean::REQUEST, name="classPlatformManager")
 */
class ClassPlatformManager
{
	public static function getSyncItemLists($class_id) :array{
		$baseInfoManager = BeanFactory::getRequestBean('baseInfoManager', (string) Co::tid());
		$objectUtil = BeanFactory::getBean('objectUtil');
		$start = microtime(true);

		$class = OnlineClassBackDao::getItem([['id', '=', $class_id]]);
		$scheduledTime = $objectUtil::maybeGetInt($class, 'scheduled_date_time') * 1000;
		$param = [];
		$param['uuid'] = $class_id;
		$param['scheduledTime'] = $scheduledTime;
		$audio_text = $baseInfoManager::getAudioTextInfo($param);
		CLog::info((string)( microtime(true) - $start));
		$start = microtime(true);

		$lesson_id = $objectUtil::maybeGetInt($class, 'lesson_id');
		$level = 0; 
		if($lesson_id){
			$lesson = CpLessonInfoDao::getItem([['lesson_id', '=', $lesson_id]]);
			$name = $objectUtil::maybeGetString($lesson, 'ppt_name');
			$match = preg_match("/L\d{1}/", $name, $name_arr);
			if($match){
				$level = (int)str_replace('L', '', $name_arr[0]);
			}
		}
		$param['level'] = $level;
		$audio_calc = $baseInfoManager::getAudioCalcInfo($param);
		CLog::info((string)( microtime(true) - $start));
		$start = microtime(true);
		$curve = $baseInfoManager::getEmotionInfo($class_id, $scheduledTime);
		CLog::info((string)( microtime(true) - $start));

		return [
			'audio_text' => $audio_text,
			'audio_calc' => $audio_calc,
			'curve' => $curve
		];
	}
	public static function getAsyncItemLists($class_id) :array{
		$objectUtil = BeanFactory::getBean('objectUtil');

		$class = OnlineClassBackDao::getItem([['id', '=', $class_id]]);
		$scheduledTime = $objectUtil::maybeGetInt($class, 'scheduled_date_time') * 1000;
		$param = [];
		$param['uuid'] = $class_id;
		$param['scheduledTime'] = $scheduledTime;
		$lesson_id = $objectUtil::maybeGetInt($class, 'lesson_id');
		$level = 0; 
		if($lesson_id){
			$lesson = CpLessonInfoDao::getItem([['lesson_id', '=', $lesson_id]]);
			$name = $objectUtil::maybeGetString($lesson, 'ppt_name');
			$match = preg_match("/L\d{1}/", $name, $name_arr);
			if($match){
				$level = (int)str_replace('L', '', $name_arr[0]);
			}
		}
		$param['level'] = $level;

        $baseInfoManager = BeanFactory::getRequestBean('baseInfoManager', (string) Co::tid());
		// 单个接口最长响应时间
		$requests = [
            'audio_text'       => function() use ($baseInfoManager, $param){
                return $baseInfoManager::getAudioTextInfo($param);
            },
            'audio_calc'       => function() use ($baseInfoManager, $param){
                return $baseInfoManager::getAudioCalcInfo($param);
            },
            'curve'       => function() use ($baseInfoManager, $param){
                return $baseInfoManager::getEmotionInfo($param['uuid'], $param['scheduledTime']);
            }
        ];

        $response = Co::multi($requests);
        return $response;
	}
}
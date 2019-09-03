<?php declare(strict_types=1);

namespace App\Ailab\Manager;

use App\Ailab\Model\Dao\CqInfoDao;
use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Bean\Annotation\Mapping\Inject;
use Swoft\Bean\BeanFactory; // 需要引入

// 可以采用 单例模式

/**
 * Class ClassQualityManager
 *
 * @since 2.0
 *
 * @Bean(scope=Bean::REQUEST, name="classQualityManager")
 */
class ClassQualityManager
{
	public static function getDataList($map, $page, $size, $orderby) :array{
		$objectUtil = BeanFactory::getBean('objectUtil');
		$list = CqInfoDao::getPage($map, $page, $size, $orderby);
		$total = CqInfoDao::getCount($map);
		foreach ($list as $lkey => &$lval) {
			// 处理问题点
			$pro_points = $objectUtil::maybeGetInt($lval, 'pro_point');
			$points = self::getProPoints($pro_points);
			$lval['point'] = implode(',', $points);
			$lval['point_count'] = count($points);
			$lval['scheduled_date_time'] = date('Y-m-d H:i', $lval['scheduled_date_time']);
		}
		unset($lval);
		$total_page = ceil($total / $size);
		$rest = [];
		$rest['total_page'] = $total_page;
		$rest['total'] = $total;
		$rest['data'] = $list;
		return $rest;
    }

    public static function getProPoints(int $num) :array{
    	$objectUtil = BeanFactory::getBean('objectUtil');
    	$mathUtil = BeanFactory::getBean('mathUtil');

    	$class_quality_points = [
        // pow(2, id-1)
	        1 => '外教迟到',
	        2 => '外教授课时长不足',
	        3 => '外教PPT未完成',
	        4 => '外教微笑不足',
	        5 => '外教ITPR不足',
	        6 => '外教语速过快',
	        7 => '投诉',
	        8 => '外教低苹果分',
	        9 => '网络低苹果分',
	        10 => '课件低苹果分',
	    ];

		if($num === 0){
			return [];
		}
		$point_arr = $mathUtil::getBinArray($num);
		sort($point_arr);
		$points = array_map(static function($item) use($objectUtil, $class_quality_points){
			return $objectUtil::maybeGetString($class_quality_points, $item);
		}, $point_arr);
		return $points;
	}

	public static function getParams() :array{
		$typesUtil = BeanFactory::getBean('typesUtil');
		$stringUtil = BeanFactory::getBean('stringUtil');
		$timeUtil = BeanFactory::getBean('timeUtil');

    	$map = [];
		$teacher_class_num = $typesUtil::safeGetString('teacher_class_num');
		$teacher_id = $typesUtil::safeGetString('teacher_id');
		$student_id = $typesUtil::safeGetString('student_id');
		$class_id = $typesUtil::safeGetString('class_id');
		$class_type = $typesUtil::safeGetString('class_type');
		$level = $typesUtil::safeGetString('level');
		$points = $typesUtil::safeGetString('pro_point');
		$is_trail = $typesUtil::safeGetString('is_trail');
		$is_ua = $typesUtil::safeGetString('is_ua');

		$start = $typesUtil::safeGetString('start');
		$start = $start ?: $timeUtil::getHistoryDayDate(61);
		$end = $typesUtil::safeGetString('end');

		if($start){
			$end = $end ? $timeUtil::formatDate(strtotime($end) + $timeUtil::TTL_ONE_DAY) : $timeUtil::getServerDateTime();
			$istart = (int)strtotime($start); // 更新之后使用
			$iend = (int)strtotime($end);
			$map[] = ['scheduled_date_time', 'between', [$istart, $iend]];
		}
		if($teacher_class_num !== '' && $teacher_class_num !== null){
			$map[] = ['teacher_class_num', '=', $teacher_class_num];
		}
		$teacher_ids = $stringUtil::getArrByDelimeter($teacher_id);
		if($teacher_ids){
			$map[] = ['teacher_id', 'in', $teacher_ids];
		}
		$student_ids = $stringUtil::getArrByDelimeter($student_id);
		if($student_ids){
			$map[] = ['student_id', 'in', $student_ids];
		}
		$class_ids = $stringUtil::getArrByDelimeter($class_id);
		if($class_ids){
			$map[] = ['class_id', 'in', $class_ids];
		}
		$point_arr = $stringUtil::getArrByDelimeter($points);
		if($point_arr){
			// $point_arr = explode(',', $points);
			// 获取每个值的所有可能组合，条件为 或 的关系
			$pro_point = self::getAllPoints($point_arr);
			$map[] = ['pro_point', 'in', $pro_point];
			// $pro_point = array_reduce($point_arr, function($carry, $item){return $carry += pow(2, $item);});
			// $map[] = ['pro_point', '=', $pro_point];
		}
		$levels = $stringUtil::getArrByDelimeter($level);
		if($levels){
			$map[] = ['level', 'in', $levels];
		}
		
		if($class_type !== '' && $class_type !== null){
			$map[] = ['class_type', '=', $class_type];
		}
		if($is_trail !== '' && $is_trail !== null){
			$map[] = ['is_trail', '=', $is_trail];
		}
		if($is_ua !== '' && $is_ua !== null){
			$map[] = ['is_ua', '=', $is_ua];
		}

		return $map;
	}

	/**
	 * 获取含有指定问题id的所有数字组合，每个问题点 或者 的关系
	 * @param  array  $points 
	 * @return array         
	 */
	private static function getAllPoints(array $points): array{
		$arrayUtil = BeanFactory::getBean('arrayUtil');
		$pro_points = CqInfoModel::getItemsByWhere('distinct(pro_point)');
		$arr = $arrayUtil::getValuesByKey($pro_points, 'pro_point');

		$ret = [];
		foreach ($points as $point) {
			$point = (int)$point + 1;
			foreach ($arr as $val) {
				$val = (int)$val;
				$str = decbin($val);
				$len = strlen($str);
				if($len < $point){
					continue;
				}
				$index = $len - $point;
				$key =$str[$index];
				if($key === '1'){
					$ret[] = $val;
				}
			}
		}
		// 如果是 且 的关系取交集
		$ret = array_unique($ret);
		return $ret;
	}
}
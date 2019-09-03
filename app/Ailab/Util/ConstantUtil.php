<?php declare(strict_types=1);

namespace App\Ailab\Util;

use Swoft\Bean\Annotation\Mapping\Bean;

/**
 * 定义常数的工具类 单例模式 存在于整个请求的生命周期，节省资源
 * Class ConstantUtil
 *
 * @since 2.0
 *
 * @Bean(scope=Bean::SINGLETON, name="constantUtil")
 */
class ConstantUtil
{
	public $audio_format_value = [
        'student_duration' => [
            1 => ['start' => 3 * 60, 'end' => 8 * 60],
            2 => ['start' => 3 * 60, 'end' => 9 * 60],
            3 => ['start' => 4 * 60, 'end' => 10 * 60],
            4 => ['start' => 5 * 60, 'end' => 12 * 60],
            5 => ['start' => 6 * 60, 'end' => 12 * 60],
            6 => ['start' => 7 * 60, 'end' => 15 * 60]
        ],
        'teacher_duration' => [
            1 => ['start' => 10 * 60, 'end' => 17 * 60],
            2 => ['start' => 9 * 60, 'end' => 16 * 60],
            3 => ['start' => 9 * 60, 'end' => 16 * 60],
            4 => ['start' => 8 * 60, 'end' => 15 * 60],
            5 => ['start' => 9 * 60, 'end' => 16 * 60],
            6 => ['start' => 8 * 60, 'end' => 16 * 60]
        ],
        'tea_audio_speed' => [
            1 => ['start' => 51, 'end' => 78],
            2 => ['start' => 63, 'end' => 100],
            3 => ['start' => 75, 'end' => 116],
            4 => ['start' => 87, 'end' => 130],
            5 => ['start' => 94, 'end' => 136],
            6 => ['start' => 105, 'end' => 149]
        ]
    ];
}
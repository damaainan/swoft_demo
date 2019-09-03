<?php declare(strict_types=1);

namespace App\Ailab\Controller;

use Co\Http\Client;
use ReflectionException;
use Swoft;
use Swoft\Bean\BeanFactory;
use Swoft\Bean\Exception\ContainerException;
use Swoft\Co;
use Swoft\Context\Context;
use Swoft\Http\Message\ContentType;
use Swoft\Http\Message\Response;
use Swoft\Http\Server\Annotation\Mapping\Controller;
use Swoft\Http\Server\Annotation\Mapping\RequestMapping;
use Swoft\Log\Helper\CLog;
use Swoft\Task\Task;
use Swoft\View\Renderer;
use Throwable;

/**
 * Class ClassQualityController
 * @Controller(prefix="cq_api")
 */
class ClassQualityController
{
	/**
	 * 分页获取数据
     * cq_api/getItemByPage?teacher_id=&class_id=&student_id=&page=1&size=20&start=2019-08-25&end=2019-09-01&level=&pro_point=10&class_type=&teacher_class_num=
     * @RequestMapping("getItemsByPage")
     *
     * @return string
     * @throws
     * @throws
     */
    public function getItemsByPage(): string
    {
        $typesUtil = BeanFactory::getBean('typesUtil');
        $page = $typesUtil::safeGetInt('page', 1);
        $size = $typesUtil::safeGetInt('size', 2);
        $classQualityManager = BeanFactory::getRequestBean('classQualityManager', (string) Co::tid());
        $map = $classQualityManager::getParams();

        $orderby = ['scheduled_date_time'=>'desc', 'class_id' => 'desc'];
        $user = $classQualityManager::getDataList($map, $page, $size, $orderby);

        // CLog::info(json_encode($user));
        return json_encode($user);

    }
}
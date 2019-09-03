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

use App\Ailab\Model\Dao\OnlineClassBackDao;

/**
 * Class ClassPlatformController
 * @Controller(prefix="cp_api")
 */
class ClassPlatformController
{
	/**
     * @RequestMapping("sync")
     * @param
     *
     * @return string
     * @throws
     * @throws
     */
    public function sync(): string
    {
        $typesUtil = BeanFactory::getBean('typesUtil');
        $class_id = $typesUtil::safeGetInt('class_id', 1);
        // CLog::info(json_encode($typesUtil));
        $classPlatformManager = BeanFactory::getRequestBean('classPlatformManager', (string) Co::tid());
        $user = $classPlatformManager::getSyncItemLists($class_id);
        return json_encode($user);
    }
    /**
     * @RequestMapping("async")
     * @param
     *
     * @return string
     * @throws
     * @throws
     */
    public function async(): string
    {
        $typesUtil = BeanFactory::getBean('typesUtil');
        $class_id = $typesUtil::safeGetInt('class_id', 1);
        // CLog::info(json_encode($typesUtil));
        $classPlatformManager = BeanFactory::getRequestBean('classPlatformManager', (string) Co::tid());
        $user = $classPlatformManager::getAsyncItemLists($class_id);
        return json_encode($user);
    }
    /**
     * @RequestMapping("test")
     * @param
     *
     * @return string
     * @throws
     * @throws
     */
    public function test(): string
    {
        $typesUtil = BeanFactory::getBean('typesUtil');
        $class_id = $typesUtil::safeGetInt('class_id', 1);
        CLog::info((string)$class_id);
        return "111";
    }
}
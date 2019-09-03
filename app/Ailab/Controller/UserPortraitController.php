<?php declare(strict_types=1);

namespace App\Ailab\Controller;

use App\Ailab\Model\Logic\UserPortraitBean;
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

use App\Ailab\Model\Entity\UpStudentEvent;
use App\Ailab\Service\UserPortraitService; // 直接 service 分层实现不了

/**
 * Class UserPortraitController
 * @Controller(prefix="userportrait_api")
 */
class UserPortraitController
{
    /**
     * @RequestMapping("index")
     * @throws Throwable
     */
    public function index(): Response
    {
        /** @var Renderer $renderer */
        $renderer = Swoft::getBean('view');
        $content  = $renderer->render('home/index');

        return Context::mustGet()->getResponse()->withContentType(ContentType::HTML)->withContent($content);
    }

    /**
     * @RequestMapping("hello[/{name}]")
     * @param string $name
     *
     * @return Response
     * @throws ReflectionException
     * @throws ContainerException
     */
    public function hello(string $name): Response
    {
        return Context::mustGet()->getResponse()->withContent('my first Hello' . ($name === '' ? '' : ", {$name}"));
    }


    /**
     * @RequestMapping("test[/{id}]")
     * @param int $id
     *
     * @return string
     * @throws ReflectionException
     * @throws ContainerException
     */
    public function test(int $id): string
    {
        // 采用 bean 注入的方式调用
        $obj = BeanFactory::getRequestBean('userPortraitManager', (string) Co::tid());
        $user = $obj->getData($id);
        // $user = UpStudentEvent::find($id);

        CLog::info(json_encode($user));
        // $user = $this->userPortraitService->getData($id);
        // return Context::mustGet()->getResponse()->withData($user);

        return json_encode($user);
    }
    /**
     * @RequestMapping("getPortrait")
     * @param
     *
     * @return void
     * @throws
     * @throws
     */
    public function getPortrait(): void
    {
        $user = UserPortraitBean::getCursorItem();
        foreach ($user as $item) {
            echo json_encode($item);
        }

    }

    /**
     * @RequestMapping("echoData")
     * @param
     *
     * @return string
     * @throws
     * @throws
     */
    public function echoData(): string
    {
        $userPortraitManager = BeanFactory::getRequestBean('userPortraitManager', (string) Co::tid());
        $user = $userPortraitManager::echoData();
        CLog::info(json_encode($user));
        return json_encode($user);

    }

    /**
     * @RequestMapping("echoItems")
     * @param
     *
     * @return string
     * @throws
     * @throws
     */
    public function echoItems(): string
    {
        $userPortraitManager = BeanFactory::getRequestBean('userPortraitManager', (string) Co::tid());
        $user = $userPortraitManager::echoItems();
        CLog::info(json_encode($user));
        return json_encode($user);

    }

    /**
     * @RequestMapping("getItemLists")
     * @param
     *
     * @return string
     * @throws
     * @throws
     */
    public function getItemLists(): string
    {
        $typesUtil = BeanFactory::getBean('typesUtil');
        $page = $typesUtil::safeGetInt('page', 1);
        $size = $typesUtil::safeGetInt('size', 2);
        // CLog::info(json_encode($typesUtil));
        $userPortraitManager = BeanFactory::getRequestBean('userPortraitManager', (string) Co::tid());
        $user = $userPortraitManager::getItemLists($page, $size);
        CLog::info(json_encode($user));
        return json_encode($user);

    }

    /**
     * @RequestMapping("testTask")
     * @param
     *
     * @return string
     * @throws
     * @throws
     */
    public function testTask(): string
    {
        $data = Task::co('testTask', 'list', [12]);
        CLog::info(json_encode($data));
        return json_encode($data);

    }

    /**
     * 并发任务 传参
     * @RequestMapping("testMulti")
     *
     * @throws \ReflectionException
     * @throws \Swoft\Bean\Exception\ContainerException
     */
    public function testMulti():string
    {
        $requests = [
            // 'method'       => [$this, 'requestMethod'],
            'method'       => function () {
                return $this->requestMethod(2,5);
            },
            //'method2'       => [$this, 'requestMethod2'],
            'method2'       => function(){
                $userPortraitManager = BeanFactory::getRequestBean('userPortraitManager', (string) Co::tid());
                return $userPortraitManager::echoItems();
            }
//            'closure'      => function () {
//                $cli = new Client('www.baidu.com', 80);
//                $cli->get('/');
//                $result = $cli->body;
//                $cli->close();
//
//                return $result;
//            }
        ];

        $response = Co::multi($requests);
        return json_encode($response);
    }

    /**
     * @return mixed
     */
    public function requestMethod($id, $num)
    {
        // sleep(3);
//        $cli = new Client('www.baidu.com', 80);
//        $cli->get('/');
//        $result = $cli->body;
//        $cli->close();
        $result = [];
        $result['id'] = $id;
        $result['num'] = $num;

        return $result;
    }

    public function requestMethod2()
    {
        sleep(2);
//        $cli = new Client('www.baidu.com', 80);
//        $cli->get('/');
//        $result = $cli->body;
//        $cli->close();

        $result = [1,2,3,4,5];

        return $result;
    }


}
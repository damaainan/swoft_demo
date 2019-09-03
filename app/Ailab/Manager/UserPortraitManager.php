<?php declare(strict_types=1);


namespace App\Ailab\Manager;

use App\Ailab\Model\Dao\UserPortraitDao;
use Swoft\Bean\Annotation\Mapping\Bean;
use App\Ailab\Model\Entity\UpStudentEvent;
use Swoft\Bean\Annotation\Mapping\Inject;
use Swoft\Bean\BeanFactory; // 需要引入

// 可以采用 单例模式

/**
 * Class UserPortraitManager
 *
 * @since 2.0
 *
 * @Bean(scope=Bean::REQUEST, name="userPortraitManager")
 */
class UserPortraitManager
{
    /**
     * 单例模式可以 注入
     * @Inject("arrayUtil")
     *
     * @var ArrayUtil
     */
    // private $arrayUtil;

    /**
     * @param  int    $id 
     * @return array
     * @throws
     */
    public function getData(int $id): array
    {
        $arrayUtil = BeanFactory::getBean('arrayUtil');
        // return ['requestBean'];
        $user = UpStudentEvent::find($id)->toArray();
        $users[] = $user;
        // $ret = [];
        // $ret = $this->arrayUtil::buildArrayByKeyValue($users, 'id', 'title');
        $ret = $arrayUtil::buildArrayByKeyValue($users, 'id', 'title');
        return $ret;
    }

    public static function echoData(): array
    {
        $arrayUtil = BeanFactory::getBean('arrayUtil');
        // return ['requestBean'];
//        $user = UserPortraitDao::getFirstItem();
        $user = UserPortraitDao::getItemsByWhere([['id', 'in', '(13590567, 13590568)']]);
//        $ret = [];
//        foreach ($user as $item) {
//            $ret[] = $item;
//        }
        return $user;
//        foreach ($user as $item) {
////            var_dump($user);
//            echo json_encode($item);
//            die();
//        }
//        $users[] = $user;
//        // $ret = [];
//        // $ret = $this->arrayUtil::buildArrayByKeyValue($users, 'id', 'title');
//        $ret = $arrayUtil::buildArrayByKeyValue($users, 'id', 'title');
//        return $ret;
    }

    public static function echoItems(): array
    {
        $param = [];
//        $param[] = ['key' => 'in', 'param' => ['id', [13590567, 13590568]]];
//        $param[] = ['key' => 'and', 'param' => [['id','<' ,13590565]]];

        $param[] = ['id','<' ,13590665];
        $order[] = ['id' => 'desc'];
        $user = UserPortraitDao::getPluck($param, $order, [], ['student_id']);
        return $user;
    }

    /**
     * @param $page
     * @param $size
     * @return array
     * @throws \ReflectionException
     * @throws \Swoft\Bean\Exception\ContainerException
     * @throws \Swoft\Db\Exception\DbException
     */
    public static function getItemLists($page, $size): array
    {
        $param = [];
        $order[] = ['id' => 'desc'];
        try {
            $users = UserPortraitDao::getPage($param, $page, $size, $order);
        } catch (Exception $e) {
            
        }
        return $users;
    }

}

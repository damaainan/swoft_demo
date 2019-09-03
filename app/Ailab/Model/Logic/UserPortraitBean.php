<?php declare(strict_types=1);


namespace App\Ailab\Model\Logic;

use App\Ailab\Model\DBModel;
use Swoft\Bean\Annotation\Mapping\Bean;
use App\Ailab\Model\Entity\UpStudentEvent;

// 在 logic 或 dao 中，每个类实现方法  共用数据库类不可靠

/**
 * Class UserPortraitBean
 *
 * @since 2.0
 *
 * @Bean(scope=Bean::REQUEST, name="userPortraitBean")
 */
class UserPortraitBean extends DBModel
{
    /**
     * @var string
     */
    public static $table = 'up_student_portrait_new';
    /**
     * @param  int    $id 
     * @return array
     * @throws
     */
//    public function getData(int $id): array
//    {
//        // return ['requestBean'];
//        $user = UpStudentEvent::find($id)->toArray();
//        return $user;
//    }

}
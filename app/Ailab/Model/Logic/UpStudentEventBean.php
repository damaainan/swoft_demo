<?php declare(strict_types=1);


namespace App\Ailab\Model\Logic;

use Swoft\Bean\Annotation\Mapping\Bean;
use App\Ailab\Model\Entity\UpStudentEvent;

/**
 * Class UpStudentEventBean
 *
 * @since 2.0
 *
 * @Bean(scope=Bean::REQUEST, name="upStudentEventBean")
 */
class UpStudentEventBean
{
    /**
     * @param  int    $id
     * @return array
     * @throws
     */
    public function getData(int $id): array
    {
        // return ['requestBean'];
        $user = UpStudentEvent::find($id)->toArray();
        return $user;
    }

}
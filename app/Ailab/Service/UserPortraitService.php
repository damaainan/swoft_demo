<?php declare(strict_types=1);

namespace App\Ailab\Service;

use App\Ailab\Lib\UserPortraitInterface;
use Exception;
use Swoft\Co;
use Swoft\Rpc\Server\Annotation\Mapping\Service;

use App\Ailab\Model\Entity\UpStudentEvent;

/**
 * Class UserPortraitService
 * @Service()
 */
class UserPortraitService implements UserPortraitInterface
{
	/**
	 * @param  int    $id 
	 * @return array    
	 */
	public function getData(int $id): array{
		$user = UpStudentEvent::find($id);
		return $user;
	}
}
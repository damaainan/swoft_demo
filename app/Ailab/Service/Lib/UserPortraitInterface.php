<?php declare(strict_types=1);


namespace App\Ailab\Lib;

/**
 * Class UserPortraitInterface
 *
 * @since 2.0
 */
interface UserPortraitInterface
{
    /**
     * @param int $id
     *
     * @return array
     */
    public function getData(int $id): array;

}
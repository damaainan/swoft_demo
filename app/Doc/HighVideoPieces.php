<?php

/**
 * Class HighVideoPieces
 *
 * @package Petstore30
 *
 * @author  jiachunhui
 * @OA\Schema(
 *     title="HighVideoPieces model",
 *     description="HighVideoPieces model",
 * )
 */
class HighVideoPieces
{

    /**
     * @OA\Property(
     *     default=3,
     *     format="int32",
     *     description="视频开始时间",
     *     title="视频开始时间",
     * )
     *
     * @var integer
     */
    private $start;

    /**
     * @OA\Property(
     *     default=3,
     *     format="int32",
     *     description="视频结束时间",
     *     title="视频结束时间",
     * )
     *
     * @var integer
     */
    private $end;
}
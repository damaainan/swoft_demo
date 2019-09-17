<?php

/**
 * Class HighVideos
 *
 * @package Petstore30
 *
 * @author  jiachunhui
 * @OA\Schema(
 *     title="HighVideos model",
 *     description="HighVideos model",
 * )
 */
class HighVideos
{

    /**
     * @OA\Property(
     *     default="http://......./video.mp4",
     *     format="string",
     *     description="视频播放地址",
     *     title="视频播放地址",
     * )
     *
     * @var string
     */
    private $url;

    /**
     * @OA\Property(
     *     default="http://activity........",
     *     format="string",
     *     description="share_link",
     *     title="分享视频链接",
     * )
     *
     * @var string
     */
    private $share_link;

    /**
     * @OA\Property(
     *     default=3,
     *     format="int32",
     *     description="精彩片段数量",
     *     title="精彩片段数量",
     * )
     *
     * @var integer
     */
    private $pieces_count;


    /**
     * @OA\Property(
     *     default="[{'start':25,'end':30},{'start':45,'end':50}]",
     *     format="array",
     *     type="array",
     *     @OA\Items(ref="#/components/schemas/HighVideoPieces"),
     *     description="精彩视频片段",
     *     title="精彩视频片段",
     * )
     *
     * @var array
     */
    private $pieces;
}
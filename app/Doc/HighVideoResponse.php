<?php

/**
 * Class ApiCode
 *
 * @author  jiachunhui
 * @OA\Schema(
 *     title="ApiCode model",
 *     description="ApiCode model",
 * )
 */
class HighVideoResponse
{

    /**
     * @OA\Property(
     *     default=200,
     *     format="int32",
     *     description="自定义响应码",
     *     title="自定义响应码",
     * )
     *
     * @var integer
     */
    private $code;
    /**
     * @OA\Property(
     *     default="成功",
     *     format="string",
     *     description="响应信息",
     *     title="响应信息",
     * )
     *
     * @var string
     */
    private $msg;

    /**
     * @OA\Property(
     *     default=1568279892.931395,
     *     format="float",
     *     description="绝对时间戳",
     *     title="绝对时间戳",
     * )
     *
     * @var float
     */
    private $m_timestamp;

    /**
     * @OA\Property(
     *     description="返回数据",
     *     title="返回数据",
     * )
     *
     * @var HighVideos
     */
    private $data;

}

/**
 * Class HighVideos
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
     *     default="我的Cindy宝贝已经在VIPKID坚持学英语71天，快来围观吧~ ",
     *     format="string",
     *     description="title",
     *     title="分享链接标题",
     * )
     *
     * @var string
     */
    private $title;

    /**
     * @OA\Property(
     *     default="0",
     *     format="int32",
     *     description="type 0.旧版横屏视频 1.新版竖屏视频",
     *     title="视频样式",
     * )
     *
     * @var integer
     */
    private $type;

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
     *     description="精彩视频片段",
     *     title="精彩视频片段",
     * )
     *
     * @var HighVideoPieces[]
     */
    private $pieces;
}

/**
 * Class HighVideoPieces
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
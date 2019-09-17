<?php

/**
 * Class ApiCode
 *
 * @package Petstore30
 *
 * @author  jiachunhui
 * @OA\Schema(
 *     title="ApiCode model",
 *     description="ApiCode model",
 * )
 */
class ApiCode
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

}
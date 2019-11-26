<?php 

// 这个文件只写接口注释
// url="http://localhost/swoft/public/index.php/",


/****************************************************************/
/****************************************************************/
/**
 * @OA\OpenApi(
 *     @OA\Info(
 *         version="1.0.0",
 *         title="Api server",
 *         @OA\License(name="MIT")
 *     ),
 *     @OA\Server(
 *         description="local Api server",
 *         url="localhost2/swoftd/public/index.php/",
 *     ),
 *     @OA\Server(
 *         description="dev Api server",
 *         url="http://10.22.60.240/",
 *     ), 
 *     @OA\Server(
 *         description="test Api server",
 *         url="http://localhost2/ailabv1/public/index.php/",
 *     )
 * )
 */

/**
 * @OA\Get(
 *   path="/api/getVideos",
 *   summary="获取视频",
 *   tags={"api"},
 *   @OA\Parameter(
 *         name="id",
 *         in="query",
 *         description="",
 *         required=true,
 *         example=123,
 *         @OA\Schema(
 *             type="integer"
 *         )
 *   ),
 *   @OA\Response(
 *     response=200,
 *     description="successful operation",
 *     @OA\JsonContent(ref="#/components/schemas/HighVideoResponse"),
 *     @OA\XmlContent(ref="#/components/schemas/HighVideoResponse")
 *   )
 * )
 */


/**
 * @OA\Get(
 *   path="/queryApi/getRankAndPreDict",
 *   summary="关单神器大数据接口",
 *   tags={"api"},
 *   @OA\Parameter(
 *         name="classId",
 *         in="query",
 *         description="课程ID",
 *         required=true,
 *         example=123,
 *         @OA\Schema(
 *             type="integer"
 *         )
 *   ),
 *   @OA\Response(
 *     response=200,
 *     description="successful operation",
 *     @OA\JsonContent(ref="#/components/schemas/RankPredictResponse"),
 *     @OA\XmlContent(ref="#/components/schemas/RankPredictResponse")
 *   )
 * )
 */

<?php

/**
 * Class RankPredictResponse
 *
 * @author  jiachunhui
 * @OA\Schema(
 *     title="RankPredictResponse model",
 *     description="RankPredictResponse model",
 * )
 */
class RankPredictResponse
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
     * @var RankPredict
     */
    private $data;

}

/**
 * Class RankPredict
 *
 * @author  jiachunhui
 * @OA\Schema(
 *     title="RankPredict model",
 *     description="RankPredict model",
 * )
 */
class RankPredict
{

    /**
     * @OA\Property(
     *     default=123456,
     *     format="string",
     *     description="学生ID",
     *     title="学生ID",
     * )
     *
     * @var integer
     */
    private $studentId;
    /**
     * @OA\Property(
     *     default="宝宝",
     *     format="string",
     *     description="学生姓名",
     *     title="学生姓名",
     * )
     *
     * @var string
     */
    private $studentName;
    /**
     * @OA\Property(
     *     default="baobao",
     *     format="string",
     *     description="学生英文姓名",
     *     title="学生英文姓名",
     * )
     *
     * @var string
     */
    private $studentNameEN;
    /**
     * @OA\Property(
     *     default=5,
     *     format="int32",
     *     description="学生年龄",
     *     title="学生年龄",
     * )
     *
     * @var integer
     */
    private $age;

    /**
     * @OA\Property(
     *     default="level1 unit 1",
     *     format="string",
     *     description="本节课标",
     *     title="本节课标",
     * )
     *
     * @var string
     */
    private $level;

    /**
     * @OA\Property(
     *     default="level1 unit 1",
     *     format="string",
     *     description="大数据同龄水平",
     *     title="大数据同龄水平",
     * )
     *
     * @var string
     */
    private $bigDataLevel;
    /**
     * @OA\Property(
     *     default=0,
     *     format="int32",
     *     description="是否已生成课中表现数据0否1是",
     *     title="是否已生成课中表现数据0否1是",
     * )
     *
     * @var integer
     */
    private $isCreate;


    /**
     * @OA\Property(
     *     default="",
     *     description="学生课中表现",
     *     title="学生课中表现",
     * )
     *
     * @var ClassPerformance
     */
    private $classPerformance;

    /**
     * @OA\Property(
     *     default="",
     *     description="学习效果预估",
     *     title="学习效果预估",
     * )
     *
     * @var ClassPredict
     */
    private $classPredict;
    /**
     * @OA\Property(
     *     default="",
     *     description="推荐人学生学习进步情况",
     *     title="推荐人学生学习进步情况",
     * )
     *
     * @var RecommendEffect
     */
    private $recommendEffect;
}


// ClassPerformance
// ClassPredict
// RecommendEffect

/**
 * Class ClassPerformance
 *
 * @author  jiachunhui
 * @OA\Schema(
 *     title="ClassPerformance model",
 *     description="ClassPerformance model",
 * )
 */
class ClassPerformance
{

    /**
     * @OA\Property(
     *     default={"current":2,"rank":0.2658,"average":5},
     *     description="开口时长数据",
     *     title="开口时长数据",
     * )
     *
     * @var RankPieces
     */
    private $talk;

    /**
     * @OA\Property(
     *     default={"current":2,"rank":0.2658,"average":5},
     *     description="专注时长数据",
     *     title="专注时长数据",
     * )
     *
     * @var RankPieces
     */
    private $focus;

    /**
     * @OA\Property(
     *     default={"current":2,"rank":0.2658,"average":5},
     *     description="互动次数数据",
     *     title="互动次数数据",
     * )
     *
     * @var RankPieces
     */
    private $interact;

    /**
     * @OA\Property(
     *     default={"current":2,"rank":0.2658,"average":5},
     *     description="词汇量数据",
     *     title="词汇量数据",
     * )
     *
     * @var RankPieces
     */
    private $words;

    /**
     * @OA\Property(
     *     default={"current":2,"rank":0.2658,"average":5},
     *     description="整句输出数据",
     *     title="整句输出数据",
     * )
     *
     * @var RankPieces
     */
    private $sentence;
}


/**
 * Class ClassPredict
 *
 * @author  jiachunhui
 * @OA\Schema(
 *     title="ClassPredict model",
 *     description="ClassPredict model",
 * )
 */
class ClassPredict
{

	/**
     * @OA\Property(
     *     default={"value":2,"averageUpgrade":3,"upgrade":5},
     *     description="开口时长数据",
     *     title="开口时长数据",
     * )
     *
     * @var AveragePredictPieces
     */
    private $talk;

    /**
     * @OA\Property(
     *     default={"value":2,"averageUpgrade":3,"upgrade":5},
     *     description="专注时长数据",
     *     title="专注时长数据",
     * )
     *
     * @var AveragePredictPieces
     */
    private $focus;

    /**
     * @OA\Property(
     *     default={"value":2,"averageUpgrade":3,"upgrade":5},
     *     description="互动次数数据",
     *     title="互动次数数据",
     * )
     *
     * @var AveragePredictPieces
     */
    private $interact;

    /**
     * @OA\Property(
     *     default={"value":2,"averageUpgrade":3,"upgrade":5},
     *     description="词汇量数据",
     *     title="词汇量数据",
     * )
     *
     * @var AveragePredictPieces
     */
    private $words;

    /**
     * @OA\Property(
     *     default={"value":2,"averageUpgrade":3,"upgrade":5},
     *     description="整句输出数据",
     *     title="整句输出数据",
     * )
     *
     * @var AveragePredictPieces
     */
    private $sentence;
}


/**
 * Class RecommendEffect
 *
 * @author  jiachunhui
 * @OA\Schema(
 *     title="RecommendEffect model",
 *     description="RecommendEffect model",
 * )
 */
class RecommendEffect
{
	/**
     * @OA\Property(
     *     default=123456,
     *     format="string",
     *     description="推荐人ID",
     *     title="推荐人ID",
     * )
     *
     * @var integer
     */
    private $recommendId;

    /**
     * @OA\Property(
     *     default={"value":2,"upgrade":5},
     *     description="开口时长数据",
     *     title="开口时长数据",
     * )
     *
     * @var PredictPieces
     */
    private $talk;

    /**
     * @OA\Property(
     *     default={"value":2,"upgrade":5},
     *     description="专注时长数据",
     *     title="专注时长数据",
     * )
     *
     * @var PredictPieces
     */
    private $focus;

    /**
     * @OA\Property(
     *     default={"value":2,"upgrade":5},
     *     description="互动次数数据",
     *     title="互动次数数据",
     * )
     *
     * @var PredictPieces
     */
    private $interact;

    /**
     * @OA\Property(
     *     default={"value":2,"upgrade":5},
     *     description="词汇量数据",
     *     title="词汇量数据",
     * )
     *
     * @var PredictPieces
     */
    private $words;

    /**
     * @OA\Property(
     *     default={"value":2,"upgrade":5},
     *     description="整句输出数据",
     *     title="整句输出数据",
     * )
     *
     * @var PredictPieces
     */
    private $sentence;
}



/**
 * Class RankPieces
 *
 * @author  jiachunhui
 * @OA\Schema(
 *     title="RankPieces model",
 *     description="RankPieces model",
 * )
 */
class RankPieces
{

    /**
     * @OA\Property(
     *     default=3,
     *     format="int32",
     *     description="当前学生的值",
     *     title="当前学生的值",
     * )
     *
     * @var integer
     */
    private $current;

    /**
     * @OA\Property(
     *     default=0.25698,
     *     format="float",
     *     description="排名",
     *     title="排名",
     * )
     *
     * @var float
     */
    private $rank;

    /**
     * @OA\Property(
     *     default=3,
     *     format="int32",
     *     description="平均值",
     *     title="平均值",
     * )
     *
     * @var integer
     */
    private $average;
}


/**
 * Class AveragePredictPieces
 *
 * @author  jiachunhui
 * @OA\Schema(
 *     title="AveragePredictPieces model",
 *     description="AveragePredictPieces model",
 * )
 */
class AveragePredictPieces
{

    /**
     * @OA\Property(
     *     default=3,
     *     format="int32",
     *     description="属性的值",
     *     title="属性的值",
     * )
     *
     * @var integer
     */
    private $value;


    /**
     * @OA\Property(
     *     default=3,
     *     format="int32",
     *     description="平均可提升数值（无课中表现数据时采用）",
     *     title="平均可提升数值（无课中表现数据时采用）",
     * )
     *
     * @var integer
     */
    private $averageUpgrade;

    /**
     * @OA\Property(
     *     default=3,
     *     format="int32",
     *     description="预估可提升数值",
     *     title="预估可提升数值",
     * )
     *
     * @var integer
     */
    private $upgrade;
}

/**
 * Class PredictPieces
 *
 * @author  jiachunhui
 * @OA\Schema(
 *     title="PredictPieces model",
 *     description="PredictPieces model",
 * )
 */
class PredictPieces
{

    /**
     * @OA\Property(
     *     default=3,
     *     format="int32",
     *     description="属性的值",
     *     title="属性的值",
     * )
     *
     * @var integer
     */
    private $value;


    /**
     * @OA\Property(
     *     default=3,
     *     format="int32",
     *     description="可提升数值",
     *     title="可提升数值",
     * )
     *
     * @var integer
     */
    private $upgrade;
}


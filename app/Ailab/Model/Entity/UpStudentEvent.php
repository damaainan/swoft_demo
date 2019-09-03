<?php declare(strict_types=1);


namespace App\Ailab\Model\Entity;

use Swoft\Db\Annotation\Mapping\Column;
use Swoft\Db\Annotation\Mapping\Entity;
use Swoft\Db\Annotation\Mapping\Id;
use Swoft\Db\Eloquent\Model;


/**
 * ????-?????
 * Class UpStudentEvent
 *
 * @since 2.0
 *
 * @Entity(table="up_student_event")
 */
class UpStudentEvent extends Model
{
    /**
     * id
     * @Id()
     * @Column()
     *
     * @var int|null
     */
    private $id;

    /**
     * ??id
     *
     * @Column(name="student_id", prop="studentId")
     *
     * @var int|null
     */
    private $studentId;

    /**
     * ??
     *
     * @Column(name="event_date", prop="eventDate")
     *
     * @var string
     */
    private $eventDate;

    /**
     * ????
     *
     * @Column()
     *
     * @var string
     */
    private $title;

    /**
     * ????
     *
     * @Column()
     *
     * @var string
     */
    private $content;


    /**
     * @param int|null $id
     *
     * @return void
     */
    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    /**
     * @param int|null $studentId
     *
     * @return void
     */
    public function setStudentId(?int $studentId): void
    {
        $this->studentId = $studentId;
    }

    /**
     * @param string $eventDate
     *
     * @return void
     */
    public function setEventDate(string $eventDate): void
    {
        $this->eventDate = $eventDate;
    }

    /**
     * @param string $title
     *
     * @return void
     */
    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    /**
     * @param string $content
     *
     * @return void
     */
    public function setContent(string $content): void
    {
        $this->content = $content;
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return int|null
     */
    public function getStudentId(): ?int
    {
        return $this->studentId;
    }

    /**
     * @return string
     */
    public function getEventDate(): string
    {
        return $this->eventDate;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }

}
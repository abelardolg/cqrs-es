<?php

declare(strict_types=1);

namespace App\Infrastructure\Task\Query\Projections;

use App\Domain\Shared\ValueObject\DateTime;

use App\Domain\Task\ValueObject\TaskDescription;
use App\Domain\Task\ValueObject\TaskName;
use Broadway\ReadModel\SerializableReadModel;
use Broadway\Serializer\Serializable;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

/**
 * @psalm-suppress MissingConstructor
 */
class TaskView implements SerializableReadModel
{
    private UuidInterface $taskID;

    private DateTime$createdAt;

    private ?DateTime $updatedAt;

    private TaskName $taskName;

    private TaskDescription $taskDescription;

    private UuidInterface $projectID;

    private function __construct(
        UuidInterface $taskID
        , DateTime $createdAt
        , ?DateTime $updatedAt
        , TaskName $taskName
        , TaskDescription $taskDescription
        , UuidInterface $projectID
    ) {
        $this->taskID= $taskID;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
        $this->taskDescription = $taskDescription;
        $this->taskName = $taskName;
        $this->projectID = $projectID;
    }

    /**
     * @throws \App\Domain\Shared\Exception\DateTimeException
     * @throws \Assert\AssertionFailedException
     */
    public static function fromSerializable(Serializable $event): self
    {
        return self::deserialize($event->serialize());
    }

    /**
     * @return TaskView
     *@throws \Assert\AssertionFailedException
     *
     * @throws \App\Domain\Shared\Exception\DateTimeException
     */
    public static function deserialize(array $data): self
    {
        return new self(
            Uuid::fromString($data['taskID'])
            , DateTime::fromString($data['created_at'])
            , isset($data['updated_at']) ? DateTime::fromString($data['updated_at']) : null
            , TaskName::fromString($data['taskName'])
            , TaskDescription::fromString($data['taskDescription'])
            , Uuid::fromString($data['projectID'])
        );
    }

    public function serialize(): array
    {
        return [
            'taskID' => $this->getId()
            , 'created_at' => $this->created_at()
            , 'updated_at' => $this->getId()
            , 'task_name' => $this->taskName()
            , 'task_description' => $this->taskDescription()
            , 'projectID' => $this->projectID()
        ];
    }

    public function taskID(): UuidInterface
    {
        return $this->taskID;
    }

    public function changeUpdatedAt(DateTime $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }

    public function getId(): string
    {
        return $this->taskID->toString();
    }

    public function taskName(): string
    {
        return $this->taskName->toString();
    }

    public function taskDescription(): string
    {
        return $this->taskDescription->toString();
    }

    public function created_at(): DateTime
    {
        return $this->createdAt;
    }

    public function projectID(): UuidInterface
    {
        return $this->projectID;
    }
}

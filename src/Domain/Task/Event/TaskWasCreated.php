<?php

declare(strict_types=1);

namespace App\Domain\Task\Event;

use App\Domain\Shared\ValueObject\DateTime;
use App\Domain\Task\ValueObject\TaskDescription;
use App\Domain\Task\ValueObject\TaskName;
use App\Domain\Shared\Exception\DateTimeException;
use Assert\Assertion;
use Assert\AssertionFailedException;
use Broadway\Serializer\Serializable;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

final class TaskWasCreated implements Serializable
{
    /**
     * @throws DateTimeException
     * @throws AssertionFailedException
     */
    public static function deserialize(array $data): self
    {
        Assertion::keyExists($data, 'taskID');
        Assertion::keyExists($data, 'taskName');
        Assertion::keyExists($data, 'taskDescription');

        return new self(
            Uuid::fromString($data['taskID']),
            DateTime::fromString($data['created_at']),
            TaskName::fromString($data['taskName']),
            TaskDescription::fromString($data['taskDescription'])
        );
    }

    public function serialize(): array
    {
        return [
            'taskID' => $this->taskID->toString(),
            'created_at' => $this->createdAt->toString(),
            'taskName' => $this->taskName->toString(),
            'taskDescription' => $this->taskDescription->toString()
        ];
    }

    public function __construct(UuidInterface $taskID,  DateTime $createdAt, TaskName $taskName, TaskDescription $taskDescription)
    {
        $this->uuid = $taskID;
        $this->createdAt = $createdAt;
        $this->taskName = $taskName;
        $this->taskDescription = $taskDescription;
    }

    /** @var UuidInterface */
    public $taskID;

    /** @var DateTime */
    public $createdAt;

    /** @var TaskName */
    public $taskName;

    /** @var TaskDescription */
    public $taskDescription;
}

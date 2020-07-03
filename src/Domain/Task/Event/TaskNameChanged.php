<?php

declare(strict_types=1);

namespace App\Domain\Task\Event;

use App\Domain\Shared\ValueObject\DateTime;
use App\Domain\Task\ValueObject\TaskName;
use Assert\Assertion;
use Broadway\Serializer\Serializable;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

final class TaskNameChanged implements Serializable
{
    /**
     * @throws \Assert\AssertionFailedException
     */
    public static function deserialize(array $data): self
    {
        Assertion::keyExists($data, 'taskID');
        Assertion::keyExists($data, 'taskName');

        return new self(
            Uuid::fromString($data['taskID']),
            TaskName::fromString($data['taskName']),
            DateTime::fromString($data['updated_at'])
        );
    }

    public function serialize(): array
    {
        return [
            'taskID' => $this->taskID->toString(),
            'taskName' => $this->taskName->toString(),
            'updated_at' => $this->updatedAt->toString(),
        ];
    }

    public function __construct(UuidInterface $taskID, TaskName $taskName, DateTime $updatedAt)
    {
        $this->taskName = $taskName;
        $this->taskID= $taskID;
        $this->updatedAt = $updatedAt;
    }

    /** @var UuidInterface */
    public $taskID;

    /** @var TaskName */
    public $taskName;

    /** @var DateTime */
    public $updatedAt;
}

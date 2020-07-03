<?php

declare(strict_types=1);

namespace App\Domain\Task\Event;

use App\Domain\Task\ValueObject\TaskDescription;
use App\Domain\Shared\ValueObject\DateTime;
use Assert\Assertion;
use Broadway\Serializer\Serializable;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

final class TaskDescriptionChanged implements Serializable
{
    /**
     * @throws \Assert\AssertionFailedException
     */
    public static function deserialize(array $data): self
    {
        Assertion::keyExists($data, 'taskID');
        Assertion::keyExists($data, 'tasktDescription');

        return new self(
            Uuid::fromString($data['taskID']),
            TaskDescription::fromString($data['taskDescription']),
            DateTime::fromString($data['updated_at'])
        );
    }

    public function serialize(): array
    {
        return [
            'taskID' => $this->taskID->toString(),
            'taskDescription' => $this->taskDescription->toString(),
            'updated_at' => $this->updatedAt->toString(),
        ];
    }

    public function __construct(UuidInterface $taskID, TaskDescription $taskDescription, DateTime $updatedAt)
    {
        $this->taskDescription = $taskDescription;
        $this->taskID= $taskID;
        $this->updatedAt = $updatedAt;
    }

    /** @var UuidInterface */
    public $taskID;

    /** @var TaskDescription */
    public $taskDescription;

    /** @var DateTime */
    public $updatedAt;
}

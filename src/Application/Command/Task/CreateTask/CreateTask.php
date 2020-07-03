<?php

declare(strict_types=1);

namespace App\Application\Command\Task\CreateTask;

use App\Domain\Task\ValueObject\TaskDescription;
use App\Domain\Task\ValueObject\TaskName;
use App\Infrastructure\Shared\Bus\Command\CommandInterface;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class CreateTask implements CommandInterface
{
    /** @psalm-readonly */
    public UuidInterface $taskID;
    /** @psalm-readonly */
    public TaskName $taskName;
    /** @psalm-readonly */
    public TaskDescription $taskDescription;

    /**
     * @throws \Assert\AssertionFailedException
     */
    public function __construct(string $taskID, string $taskName, string $taskDescription)
    {
        $this->taskID = Uuid::fromString($taskID);
        $this->taskName = TaskName::fromString($taskName);
        $this->taskDescription = TaskDescription::fromString($taskDescription);
    }
}

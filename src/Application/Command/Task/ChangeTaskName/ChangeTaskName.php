<?php

declare(strict_types=1);

namespace App\Application\Command\Task\ChangeTaskName;

use App\Domain\Task\ValueObject\TaskName;
use App\Infrastructure\Shared\Bus\Command\CommandInterface;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class ChangeTaskName implements CommandInterface
{
    /** @psalm-readonly */
    public UuidInterface $taskID;

    /** @psalm-readonly */
    public TaskName $taskName;

    /**
     * @throws \Assert\AssertionFailedException
     */
    public function __construct(string $taskID, string $taskName)
    {
        $this->taskID = Uuid::fromString($taskID);
        $this->taskName= TaskName::fromString($taskName);
    }
}

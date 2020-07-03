<?php

declare(strict_types=1);

namespace App\Domain\Task\Repository;

use App\Domain\Task\ValueObject\TaskName;
use Ramsey\Uuid\UuidInterface;

interface CheckTaskNameInterface
{
    public function existsTaskName(TaskName $taskName): ?UuidInterface;
}

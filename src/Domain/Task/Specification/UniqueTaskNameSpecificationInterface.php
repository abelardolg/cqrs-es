<?php

declare(strict_types=1);

namespace App\Domain\Task\Specification;

use App\Domain\Task\Exception\TaskNameAlreadyExistException;
use App\Domain\Task\ValueObject\TaskName;

interface UniqueTaskNameSpecificationInterface
{
    /**
     * @throws TaskNameAlreadyExistException
     */
    public function isUnique(TaskName $taskName): bool;
}

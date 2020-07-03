<?php

declare(strict_types=1);

namespace App\Infrastructure\Task\Specification;

use App\Domain\Project\Exception\ProjectNameAlreadyExistException;
use App\Domain\Task\Exception\TaskNameAlreadyExistException;
use App\Domain\Task\Repository\CheckTaskNameInterface;
use App\Domain\Task\Specification\UniqueTaskNameSpecificationInterface;
use App\Domain\Task\ValueObject\TaskName;
use App\Domain\Shared\Specification\AbstractSpecification;

final class UniqueTaskNameSpecification extends AbstractSpecification implements UniqueTaskNameSpecificationInterface
{
    private CheckTaskNameInterface $checkTaskByTaskName;

    public function __construct(CheckTaskNameInterface $checkTaskByTaskName)
    {
        $this->checkTaskByTaskName = $checkTaskByTaskName;
    }

    /**
     * @throws ProjectNameAlreadyExistException
     */
    public function isUnique(TaskName $taskName): bool
    {
        return $this->isSatisfiedBy($taskName);
    }

    /**
     * @param TaskName $value
     * @psalm-suppress MoreSpecificImplementedParamType
     */
    public function isSatisfiedBy($value): bool
    {
        if ($this->checkTaskByTaskName->existsTaskName($value)) {
            throw new TaskNameAlreadyExistException();
        }

        return true;
    }
}

<?php

declare(strict_types=1);

namespace App\Application\Command\Task\ChangeTaskName;

use App\Domain\Task\Repository\TaskRepositoryInterface;
use App\Domain\Task\Specification\UniqueTaskNameSpecificationInterface;
use App\Infrastructure\Shared\Bus\Command\CommandHandlerInterface;

class ChangeTaskNameHandler implements CommandHandlerInterface
{
    private TaskRepositoryInterface $taskRepository;
    private UniqueTaskNameSpecificationInterface $uniqueTaskNameSpecification;

    public function __construct(
        TaskRepositoryInterface $taskRepository,
        UniqueTaskNameSpecificationInterface $uniqueTaskNameSpecification
    ) {
        $this->taskRepository = $taskRepository;
        $this->uniqueTaskNameSpecification = $uniqueTaskNameSpecification;
    }

    public function __invoke(ChangeTaskName $command): void
    {
        $task = $this->taskRepository->get($command->taskID);

        $task->changeTaskName($command->taskName, $this->uniqueTaskNameSpecification);

        $this->taskRepository->store($task);
    }
}

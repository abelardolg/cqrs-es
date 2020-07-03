<?php

declare(strict_types=1);

namespace App\Application\Command\Task\CreateTask;

use App\Domain\Task\Repository\TaskRepositoryInterface;
use App\Domain\Task\Specification\UniqueTaskNameSpecificationInterface;
use App\Domain\Task\Task;
use App\Infrastructure\Shared\Bus\Command\CommandHandlerInterface;

class CreateTaskHandler implements CommandHandlerInterface
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

    /**
     * @throws \App\Domain\Shared\Exception\DateTimeException
     */
    public function __invoke(CreateTask $command): void
    {
        $task = Task::create($command->taskID, $command->taskName, $command->taskDescription, $this->uniqueTaskNameSpecification);

        $this->taskRepository->store($task);
    }
}

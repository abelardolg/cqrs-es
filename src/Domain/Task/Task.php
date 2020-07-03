<?php

declare(strict_types=1);

namespace App\Domain\Task;

use App\Domain\Shared\Exception\DateTimeException;
use App\Domain\Shared\ValueObject\DateTime;

use App\Domain\Task\Event\TaskDescriptionChanged;
use App\Domain\Task\Event\TaskNameChanged;
use App\Domain\Task\Event\TaskWasCreated;

use App\Domain\Task\Specification\UniqueTaskNameSpecificationInterface;

use App\Domain\Task\ValueObject\TaskDescription;
use App\Domain\Task\ValueObject\TaskName;

use Assert\Assertion;
use Broadway\EventSourcing\EventSourcedAggregateRoot;
use Ramsey\Uuid\UuidInterface;

/**
 * @psalm-suppress MissingConstructor
 */
class Task extends EventSourcedAggregateRoot
{
    /** @var UuidInterface */
    private $taskID;

    /** @var DateTime */
    private $createdAt;

    /** @var DateTime|null */
    private $updatedAt;

    /** @var TaskName */
    private $taskName;

    /** @var TaskDescription */
    private $taskDescription;

    /**
     * @throws DateTimeException
     */
    public static function create(
        UuidInterface $taskID
        , TaskName $taskName
        , TaskDescription $taskDescription
        , UniqueTaskNameSpecificationInterface $uniqueTaskNameSpecification
    ): self {
        $uniqueTaskNameSpecification->isUnique($taskName);

        $task = new self();

        $task->apply(new TaskWasCreated($taskID, DateTime::now(), $taskName, $taskDescription));

        return $task;
    }

    /**
     * @throws DateTimeException
     */
    public function changeTaskName(
        TaskName $taskName,
        UniqueTaskNameSpecificationInterface $uniqueTaskNameSpecification
    ): void {
        $uniqueTaskNameSpecification->isUnique($taskName);
        $this->apply(new TaskNameChanged($this->taskID, $taskName, DateTime::now()));
    }

    /**
     * @throws DateTimeException
     */
    public function changeTaskDescription(
        TaskDescription $taskDescription
    ): void {
        $this->apply(new TaskDescriptionChanged($this->taskID, $taskDescription, DateTime::now()));
    }

    protected function applyTaskWasCreated(TaskWasCreated $event): void
    {
        $this->taskID= $event->taskID;

        $this->setCreatedAt($event->createdAt);
        $this->setTaskName($event->taskName);
        $this->setTaskDescription($event->taskDescription);
    }

    /**
     * @throws \Assert\AssertionFailedException
     */
    protected function applyTaskNameChanged(TaskNameChanged $event): void
    {
        Assertion::notEq($this->taskName->toString(), $event->taskName->toString(), 'El nuevo nombre debería ser diferente');

        $this->setTaskName($event->taskName);
        $this->setUpdatedAt($event->updatedAt);
    }

    private function setTaskName(TaskName $taskName): void
    {
        $this->taskName= $taskName;
    }

    private function setCreatedAt(DateTime $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    private function setUpdatedAt(DateTime $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }

    private function setTaskDescription(TaskDescription $taskDescription): void
    {
        $this->taskDescription = $taskDescription;
    }

    public function createdAt(): string
    {
        return $this->createdAt->toString();
    }

    public function updatedAt(): ?string
    {
        return isset($this->updatedAt) ? $this->updatedAt->toString() : null;
    }

    public function taskID(): string
    {
        return $this->taskID->toString();
    }

    public function taskName(): string
    {
        return $this->taskName->toString();
    }

    public function taskDescription(): string
    {
        return $this->taskDescription->toString();
    }

    public function getAggregateRootId(): string
    {
        return $this->taskID->toString();
    }
}

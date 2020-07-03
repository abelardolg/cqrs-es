<?php

declare(strict_types=1);

namespace App\Infrastructure\Task\Repository;

use App\Domain\Task\Repository\TaskRepositoryInterface;
use App\Domain\Task\Task;
use Broadway\EventHandling\EventBus;
use Broadway\EventSourcing\AggregateFactory\PublicConstructorAggregateFactory;
use Broadway\EventSourcing\EventSourcingRepository;
use Broadway\EventStore\EventStore;
use Ramsey\Uuid\UuidInterface;

final class TaskStore extends EventSourcingRepository implements TaskRepositoryInterface
{
    public function __construct(
        EventStore $eventStore,
        EventBus $eventBus,
        array $eventStreamDecorators = []
    ) {
        parent::__construct(
            $eventStore,
            $eventBus,
            Task::class,
            new PublicConstructorAggregateFactory(),
            $eventStreamDecorators
        );
    }

    public function store(Task $task): void
    {
        $this->save($task);
    }

    public function get(UuidInterface $uuid): Task
    {
        /** @var Task $user */
        $user = $this->load($uuid->toString());

        return $user;
    }
}

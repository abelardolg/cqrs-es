<?php

declare(strict_types=1);

namespace App\Infrastructure\Project\Repository;

use App\Domain\Project\Repository\ProjectRepositoryInterface;
use App\Domain\Project\Project;
use Broadway\EventHandling\EventBus;
use Broadway\EventSourcing\AggregateFactory\PublicConstructorAggregateFactory;
use Broadway\EventSourcing\EventSourcingRepository;
use Broadway\EventStore\EventStore;
use Ramsey\Uuid\UuidInterface;

final class ProjectStore extends EventSourcingRepository implements ProjectRepositoryInterface
{
    public function __construct(
        EventStore $eventStore,
        EventBus $eventBus,
        array $eventStreamDecorators = []
    ) {
        parent::__construct(
            $eventStore,
            $eventBus,
            Project::class,
            new PublicConstructorAggregateFactory(),
            $eventStreamDecorators
        );
    }

    public function store(Project $project): void
    {
        $this->save($project);
    }

    public function get(UuidInterface $projectID): Project
    {
        /** @var Project $project */
        $project = $this->load($projectID->toString());

        return $project;
    }
}

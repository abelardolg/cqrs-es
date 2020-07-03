<?php

declare(strict_types=1);

namespace App\Infrastructure\Shared\Event\Consumer;

use App\Infrastructure\Shared\Bus\Event\Event;
use App\Infrastructure\Shared\Bus\Event\EventHandlerInterface;
use App\Infrastructure\Shared\Event\Query\EventElasticRepository;

class SendEventsToElasticConsumer implements EventHandlerInterface
{
    private EventElasticRepository $eventElasticRepository;

    public function __construct(EventElasticRepository $eventElasticRepository)
    {
        $this->eventElasticRepository = $eventElasticRepository;
    }

    public function __invoke(Event $event): void
    {
        $this->eventElasticRepository->store(
            $event->getDomainMessage()
        );
    }
}

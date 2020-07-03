<?php

declare(strict_types=1);

namespace App\Tests\Application\Query\Event\GetEvents;

use App\Application\Command\User\SignUp\SignUp;
use App\Application\Query\Event\GetEvents\GetEventsQuery;
use App\Domain\User\Event\UserWasCreated;
use App\Infrastructure\Shared\Bus\Event\Event;
use App\Infrastructure\Shared\Bus\Query\Collection;
use App\Infrastructure\Shared\Event\Consumer\SendEventsToElasticConsumer;
use App\Infrastructure\Shared\Event\Query\EventElasticRepository;
use App\Tests\Application\ApplicationTestCase;
use Broadway\Domain\DateTime;
use Broadway\Domain\DomainMessage;
use Broadway\Domain\Metadata;
use Ramsey\Uuid\Uuid;

final class GetEventsTest extends ApplicationTestCase
{
    /**
     * @throws \App\Domain\Shared\Exception\DateTimeException
     * @throws \Assert\AssertionFailedException
     * @throws \Throwable
     */
    protected function setUp(): void
    {
        parent::setUp();

        /** @var EventElasticRepository $eventReadStore */
        $eventReadStore = $this->service(EventElasticRepository::class);
        $eventReadStore->reboot();

        $command = new SignUp(
            Uuid::uuid4()->toString(),
            'asd@asd.asd',
            'qwerqwer'
        );

        $this->handle($command);

        /** @var SendEventsToElasticConsumer $consumer */
        $consumer = $this->service(SendEventsToElasticConsumer::class);
        $data = [
            'uuid' => $uuid = Uuid::uuid4()->toString(),
            'credentials' => [
                'email' => 'asd@asd.asd',
                'password' => 'lkasjbdalsjdbalsdbaljsdhbalsjbhd987',
            ],
            'created_at' => '2020-02-20',
        ];
        $consumer(new Event(
            new DomainMessage(
                $uuid,
                1,
                new Metadata(),
                ProjectWasCreated::deserialize($data),
                DateTime::now()
            )
        ));

        $this->fireTerminateEvent();

        /** @var EventElasticRepository $eventReadStore */
        $eventReadStore = $this->service(EventElasticRepository::class);
        $eventReadStore->refresh();
    }

    /**
     * @test
     *
     * @group integration
     */
    public function processed_events_must_be_in_elastic_search(): void
    {
        $response = $this->ask(new GetEventsQuery());

        self::assertInstanceOf(Collection::class, $response);
        self::assertSame(1, $response->total);
        self::assertSame('App.Domain.User.Event.UserWasCreated', $response->data[0]['type']);
    }

    protected function tearDown(): void
    {
        /** @var EventElasticRepository $eventReadStore */
        $eventReadStore = $this->service(EventElasticRepository::class);
        $eventReadStore->delete();

        parent::tearDown();
    }
}

<?php

declare(strict_types=1);

namespace App\Application\Query\Event\GetEvents;

use App\Infrastructure\Shared\Bus\Query\QueryInterface;

class GetEventsQuery implements QueryInterface
{
    /** @psalm-readonly */
    public int $page;

    /** @psalm-readonly */
    public int $limit;

    public function __construct(int $page = 1, int $limit = 50)
    {
        $this->page = $page;
        $this->limit = $limit;
    }
}

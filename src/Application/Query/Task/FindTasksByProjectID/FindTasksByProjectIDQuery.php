<?php

declare(strict_types=1);

namespace App\Application\Query\Task\FindTasksByProjectID;

use App\Infrastructure\Shared\Bus\Query\QueryInterface;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class FindTasksByProjectIDQuery implements QueryInterface
{
    /** @psalm-readonly */
    /** @var UuidInterface*/
    public $projectID;

    /**
     * @throws \Assert\AssertionFailedException
     */
    public function __construct(string $projectID)
    {
        $this->projectID = Uuid::fromString($projectID);
    }
}

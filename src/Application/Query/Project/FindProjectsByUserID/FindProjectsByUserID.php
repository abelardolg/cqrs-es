<?php

declare(strict_types=1);

namespace App\Application\Query\Project\FindProjectsByUserID;

use App\Infrastructure\Shared\Bus\Query\QueryInterface;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class FindProjectsByUserID implements QueryInterface
{
    /** @psalm-readonly */
    /** @var UuidInterface*/
    public $userID;

    /**
     * @throws \Assert\AssertionFailedException
     */
    public function __construct(string $userID)
    {
        $this->userID = Uuid::fromString($userID);
    }
}

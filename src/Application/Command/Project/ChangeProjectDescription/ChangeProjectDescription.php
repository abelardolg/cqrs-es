<?php

declare(strict_types=1);

namespace App\Application\Command\Project\ChangeProjectDescription;

use App\Domain\Project\ValueObject\ProjectDescription;
use App\Infrastructure\Shared\Bus\Command\CommandInterface;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class ChangeProjectDescription implements CommandInterface
{
    /** @psalm-readonly */
    public UuidInterface $projectID;

    /** @psalm-readonly */
    public ProjectDescription $projectDescription;

    /**
     * @throws \Assert\AssertionFailedException
     */
    public function __construct(string $projectID, string $projectDescription)
    {
        $this->projectID = Uuid::fromString($projectID);
        $this->projectDescription = ProjectDescription::fromString($projectDescription);
    }
}

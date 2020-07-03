<?php

declare(strict_types=1);

namespace App\Application\Command\Project\ChangeProjectName;

use App\Domain\Project\ValueObject\ProjectName;
use App\Infrastructure\Shared\Bus\Command\CommandInterface;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class ChangeProjectName implements CommandInterface
{
    /** @psalm-readonly */
    public UuidInterface $projectID;

    /** @psalm-readonly */
    public ProjectName $projectName;

    /**
     * @throws \Assert\AssertionFailedException
     */
    public function __construct(string $projectID, string $projectName)
    {
        $this->projectID = Uuid::fromString($projectID);
        $this->projectName = ProjectName::fromString($projectName);
    }
}

<?php

declare(strict_types=1);

namespace App\Application\Command\Project\CreateProject;

use App\Domain\Project\ValueObject\ProjectDescription;
use App\Domain\Project\ValueObject\ProjectName;
use App\Domain\Shared\ValueObject\DateTime;
use App\Infrastructure\Shared\Bus\Command\CommandInterface;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class CreateProject implements CommandInterface
{
    /** @psalm-readonly */
    public UuidInterface $projectID;
    /** @psalm-readonly */
    public UuidInterface $userID;
    /** @psalm-readonly */
    public DateTime $createdAt;
    /** @psalm-readonly */
    public ProjectName $projectName;
    /** @psalm-readonly */
    public ProjectDescription $projectDescription;

    /**
     * @throws \Assert\AssertionFailedException
     */
    public function __construct(string $projectID, string $projectName, string $projectDescription, string $userID)
    {
        $this->projectID = Uuid::fromString($projectID);
        $this->userID = Uuid::fromString($userID);
        $this->createdAt = DateTime::now();
        $this->projectName = ProjectName::fromString($projectName);
        $this->projectDescription = ProjectDescription::fromString($projectDescription);
    }
}

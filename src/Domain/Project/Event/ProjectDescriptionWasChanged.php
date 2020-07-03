<?php

declare(strict_types=1);

namespace App\Domain\Project\Event;

use App\Domain\Project\ValueObject\ProjectDescription;
use App\Domain\Shared\ValueObject\DateTime;
use Assert\Assertion;
use Broadway\Serializer\Serializable;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

final class ProjectDescriptionWasChanged implements Serializable
{
    /**
     * @throws \Assert\AssertionFailedException
     */
    public static function deserialize(array $data): self
    {
        Assertion::keyExists($data, 'projectID');
        Assertion::keyExists($data, 'projectDescription');

        return new self(
            Uuid::fromString($data['projectID']),
            ProjectDescription::fromString($data['projectDescrption']),
            DateTime::fromString($data['updated_at'])
        );
    }

    public function serialize(): array
    {
        return [
            'projectID' => $this->projectID->toString(),
            'projectDescription' => $this->projectDescription->toString(),
            'updated_at' => $this->updatedAt->toString(),
        ];
    }

    public function __construct(UuidInterface $projectID, ProjectDescription $projectDescription, DateTime $updatedAt)
    {
        $this->projectDescription = $projectDescription;
        $this->projectID= $projectID;
        $this->updatedAt = $updatedAt;
    }

    /** @var UuidInterface */
    public $projectID;

    /** @var ProjectDescription */
    public $projectDescription;

    /** @var DateTime */
    public $updatedAt;
}

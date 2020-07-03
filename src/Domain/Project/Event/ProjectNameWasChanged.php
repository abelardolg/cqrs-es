<?php

declare(strict_types=1);

namespace App\Domain\Project\Event;

use App\Domain\Shared\ValueObject\DateTime;
use App\Domain\Project\ValueObject\ProjectName;
use Assert\Assertion;
use Broadway\Serializer\Serializable;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

final class ProjectNameWasChanged implements Serializable
{
    /**
     * @throws \Assert\AssertionFailedException
     */
    public static function deserialize(array $data): self
    {
        Assertion::keyExists($data, 'projectID');
        Assertion::keyExists($data, 'projectName');
        Assertion::keyExists($data, 'createdAt');

        return new self(
            Uuid::fromString($data['projectID']),
            ProjectName::fromString($data['projectName']),
            DateTime::fromString($data['updated_at'])
        );
    }

    public function serialize(): array
    {
        return [
            'projectID' => $this->projectID->toString()
            , 'projectName' => $this->projectName->toString()
            , 'updated_at' => $this->updatedAt->toString()
        ];
    }

    public function __construct(UuidInterface $projectID, ProjectName $projectName, DateTime $updatedAt)
    {
        $this->projectName= $projectName;
        $this->projectID = $projectID;
        $this->updatedAt = $updatedAt;
    }

    /** @var UuidInterface */
    public $projectID;

    /** @var ProjectName */
    public $projectName;

    /** @var DateTime */
    public $updatedAt;
}

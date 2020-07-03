<?php

declare(strict_types=1);

namespace App\Domain\Project\Event;

use App\Domain\Project\ValueObject\ProjectDescription;
use App\Domain\Project\ValueObject\ProjectName;
use App\Domain\Shared\Exception\DateTimeException;
use App\Domain\Shared\ValueObject\DateTime;
use Assert\Assertion;
use Assert\AssertionFailedException;
use Broadway\Serializer\Serializable;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

final class ProjectWasCreated implements Serializable
{
    /**
     * @throws DateTimeException
     * @throws AssertionFailedException
     */
    public static function deserialize(array $data): self
    {
        Assertion::keyExists($data, 'projectID');
        Assertion::keyExists($data, 'projectName');
        Assertion::keyExists($data, 'projectDescription');

        return new self(
            Uuid::fromString($data['projectID'])
            , DateTime::fromString($data['created_at'])
            , ProjectName::fromString($data['projectName'])
            , ProjectDescription::fromString($data['projectDescription'])
            , Uuid::fromString($data['userID'])
        );
    }

    public function serialize(): array
    {
        return [
            'projectID' => $this->projectID->toString()
            , 'created_at' => $this->createdAt->toString()
            , 'projectName' => $this->projectName->toString()
            , 'projectDescription' => $this->projectDescription->toString()
            , 'userID' => $this->userID->toString(),
            ];
    }

    public function __construct(UuidInterface $projectID, DateTime $createdAt, ProjectName $projectName, ProjectDescription $projectDescription, UuidInterface $userID)
    {
        $this->projectID = $projectID;
        $this->createdAt = $createdAt;
        $this->projectName = $projectName;
        $this->projectDescription = $projectDescription;
        $this->userID = $userID;
    }

    /** @var UuidInterface */
    public $projectID;

    /** @var DateTime */
    public $createdAt;

    /** @var ProjectName */
    public $projectName;

    /** @var ProjectDescription */
    public $projectDescription;

    /** @var UuidInterface */
    public $userID;
}

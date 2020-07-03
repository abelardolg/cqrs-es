<?php

declare(strict_types=1);

namespace App\Infrastructure\Project\Query\Projections;

use App\Domain\Shared\ValueObject\DateTime;
use App\Domain\Project\ValueObject\ProjectDescription;
use App\Domain\Project\ValueObject\ProjectName;
use App\Infrastructure\User\Query\Projections\UserView;
use Broadway\ReadModel\SerializableReadModel;
use Broadway\Serializer\Serializable;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

/**
 * @psalm-suppress MissingConstructor
 */
class ProjectView implements SerializableReadModel
{
    private UuidInterface $projectID;

    private DateTime$createdAt;

    private ?DateTime $updatedAt;

    private ProjectName $projectName;

    private ProjectDescription $projectDescription;

    private UuidInterface $userID;

    private function __construct(
        UuidInterface $projectID
        , DateTime $createdAt
        , ?DateTime $updatedAt
        , ProjectName $projectName
        , ProjectDescription $projectDescription
        , UuidInterface $userID

    ) {
        $this->projectID= $projectID;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
        $this->projectDescription = $projectDescription;
        $this->projectName = $projectName;
        $this->userID = $userID;
    }

    /**
     * @throws \App\Domain\Shared\Exception\DateTimeException
     * @throws \Assert\AssertionFailedException
     */
    public static function fromSerializable(Serializable $event): self
    {
        return self::deserialize($event->serialize());
    }

    /**
     * @return ProjectView
     *@throws \Assert\AssertionFailedException
     *
     * @throws \App\Domain\Shared\Exception\DateTimeException
     */
    public static function deserialize(array $data): self
    {
        return new self(
            Uuid::fromString($data['projectID'])
            , DateTime::fromString($data['created_at'])
            , isset($data['updated_at']) ? DateTime::fromString($data['updated_at']) : null
            , ProjectName::fromString($data['projectName'])
            , ProjectDescription::fromString($data['projectDescription'])
            ,  Uuid::fromString($data['userID'])
        );
    }

    public function serialize(): array
    {
        return [
            'projectID' => $this->getId()
            ,'created_at' => $this->createdAt
            ,'updated_at' => $this->createdAt
            ,'projectName' => $this->projectName
            ,'projectDescription' => $this->projectDescription
            ,'userID' => $this->userID
        ];
    }

    public function projectID(): UuidInterface
    {
        return $this->projectID;
    }
    public function createdAt(): DateTime
    {
        return $this->createdAt;
    }
    public function updatedAt(): DateTime
    {
        return $this->updatedAt;
    }
    public function changeUpdatedAt(DateTime $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }

    public function getId(): string
    {
        return $this->projectID->toString();
    }

    public function projectName(): string
    {
        return $this->projectName->toString();
    }

    public function changeProjectName(ProjectName $projectName): void
    {
        $this->projectName = $projectName;
    }
    public function projectDescription(): string
    {
        return $this->projectDescription->toString();
    }
    public function userID(): UuidInterface
    {
        return $this->userID;
    }
}

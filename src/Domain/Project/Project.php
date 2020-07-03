<?php

declare(strict_types=1);

namespace App\Domain\Project;

use App\Domain\Project\Event\ProjectNameWasChanged;
use App\Domain\Project\Event\ProjectWasCreated;
use App\Domain\Project\ValueObject\ProjectDescription;
use App\Domain\Shared\Exception\DateTimeException;
use App\Domain\Shared\ValueObject\DateTime;

use App\Domain\Project\Specification\UniqueProjectNameSpecificationInterface;

use App\Domain\Project\ValueObject\ProjectName;

use Broadway\EventSourcing\EventSourcedAggregateRoot;
use Ramsey\Uuid\UuidInterface;

/**
 * @psalm-suppress MissingConstructor
 */
class Project extends EventSourcedAggregateRoot
{
    /** @var UuidInterface */
    private $projectID;

    /** @var UuidInterface */
    private $userID;

    /** @var DateTime */
    private $createdAt;

    /** @var DateTime|null */
    private $updatedAt;

    /** @var ProjectName */
    private $projectName;

    /** @var ProjectDescription */
    private $projectDescription;

    /**
     * @throws DateTimeException
     */
    public static function create(
        UuidInterface $projectID
        , ProjectName $projectName
        , ProjectDescription $projectDescription
        , UuidInterface $userID
        , UniqueProjectNameSpecificationInterface $uniqueProjectNameSpecification
    ): self {

        $uniqueProjectNameSpecification->isUnique($projectName);

        $project = new self();

        $project->apply(new ProjectWasCreated($projectID,  DateTime::now(), $projectName, $projectDescription, $userID));
        return $project;
    }

    public function changeProjectDescription(ProjectDescription $projectDescription) : void
    {
        $this->projectDescription = $projectDescription;
    }

    public function changeProjectName(ProjectName $projectName, UniqueProjectNameSpecificationInterface $uniqueProjectNameSpecification) : void
    {
        $uniqueProjectNameSpecification->isUnique($projectName);
        $this->apply(new ProjectNameWasChanged($this->projectID, $projectName, DateTime::now()));
    }

    protected function applyProjectWasCreated(ProjectWasCreated $event): void
    {
        $this->projectID = $event->projectID;

        $this->setCreatedAt($event->createdAt);
        $this->setProjectName($event->projectName);
        $this->setProjectDescription($event->projectDescription);
        $this->setUserID($event->userID);
    }

    private function setCreatedAt(DateTime $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    private function setUpdatedAt(DateTime $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }

    private function setProjectName(ProjectName $projectName): void
    {
        $this->projectName= $projectName;
    }

    private function setProjectDescription(ProjectDescription $projectDescription): void
    {
        $this->projectDescription = $projectDescription;
    }

    private function setUserID(UuidInterface $userID): void
    {
        $this->userID = $userID;
    }

    public function createdAt(): string
    {
        return $this->createdAt->toString();
    }

    public function updatedAt(): ?string
    {
        return isset($this->updatedAt) ? $this->updatedAt->toString() : null;
    }

    public function projectID(): string
    {
        return $this->projectID->toString();
    }

    public function projectName(): string
    {
        return $this->projectName->toString();
    }

    public function projectDescription(): string
    {
        return $this->projectDescription->toString();
    }

    public function getAggregateRootId(): string
    {
        return $this->projectID->toString();
    }

    public function userID(): string
    {
        return $this->userID->toString();
    }
}

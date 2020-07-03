<?php

declare(strict_types=1);

namespace App\Infrastructure\Project\Query;

use App\Domain\Project\Event\ProjectNameWasChanged;
use App\Domain\Project\Event\ProjectWasCreated;
use App\Infrastructure\Project\Query\Mysql\MysqlProjectReadModelRepository;
use App\Infrastructure\Project\Query\Projections\ProjectView;
use Broadway\ReadModel\Projector;

class ProjectProjectionFactory extends Projector
{
    private MysqlProjectReadModelRepository $repository;

    public function __construct(MysqlProjectReadModelRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @throws \Assert\AssertionFailedException
     */
    protected function applyProjectWasCreated(ProjectWasCreated $projectrWasCreated): void
    {
        $projectReadModel = ProjectView::fromSerializable($projectrWasCreated);

        $this->repository->add($projectReadModel);
    }

    /**
     * @throws \App\Domain\Shared\Query\Exception\NotFoundException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    protected function applyProjectNameWasChanged(ProjectNameWasChanged $projectNameWasChanged): void
    {
        /** @var ProjectView $projectReadModel */
        $projectReadModel = $this->repository->oneByUuid($projectNameWasChanged->projectID);

        $projectReadModel->changeProjectName($projectNameWasChanged->projectName);
        $projectReadModel->changeUpdatedAt($projectNameWasChanged->updatedAt);

        $this->repository->apply();
    }
}

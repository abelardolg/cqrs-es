<?php

declare(strict_types=1);

namespace App\Application\Command\Project\CreateProject;

use App\Domain\Project\Repository\ProjectRepositoryInterface;
use App\Domain\Project\Specification\UniqueProjectNameSpecificationInterface;
use App\Domain\Project\Project;
use App\Infrastructure\Shared\Bus\Command\CommandHandlerInterface;

class CreateProjectHandler implements CommandHandlerInterface
{
    private ProjectRepositoryInterface $projectRepository;

    private UniqueProjectNameSpecificationInterface $uniqueProjectNameSpecification;

    public function __construct(
        ProjectRepositoryInterface $projectRepository,
        UniqueProjectNameSpecificationInterface $uniqueProjectNameSpecification
    ) {
        $this->projectRepository = $projectRepository;
        $this->uniqueProjectNameSpecification= $uniqueProjectNameSpecification;
    }

    /**
     * @throws \App\Domain\Shared\Exception\DateTimeException
     */
    public function __invoke(CreateProject $command): void
    {
        $project = Project::create($command->projectID, $command->projectName, $command->projectDescription, $command->userID, $this->uniqueProjectNameSpecification);
        $this->projectRepository->store($project);
    }
}

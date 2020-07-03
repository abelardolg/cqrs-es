<?php

declare(strict_types=1);

namespace App\Application\Command\Project\ChangeProjectName;

use App\Domain\Project\Repository\ProjectRepositoryInterface;
use App\Domain\Project\Specification\UniqueProjectNameSpecificationInterface;
use App\Infrastructure\Shared\Bus\Command\CommandHandlerInterface;

class ChangeProjectNameHandler implements CommandHandlerInterface
{
    private ProjectRepositoryInterface $projectRepository;
    private UniqueProjectNameSpecificationInterface $uniqueProjectNameSpecification;

    public function __construct(
        ProjectRepositoryInterface $projectRepository,
        UniqueProjectNameSpecificationInterface $uniqueProjectNameSpecification
    ) {
        $this->projectRepository = $projectRepository;
        $this->uniqueProjectNameSpecification = $uniqueProjectNameSpecification;
    }

    public function __invoke(ChangeProjectName $command): void
    {
        $project = $this->projectRepository->get($command->projectID);

        $project->changeProjectName($command->projectName, $this->uniqueProjectNameSpecification);

        $this->projectRepository->store($project);
    }
}

<?php

declare(strict_types=1);

namespace App\Application\Command\Project\ChangeProjectDescription;

use App\Domain\Project\Repository\ProjectRepositoryInterface;
use App\Infrastructure\Shared\Bus\Command\CommandHandlerInterface;

class ChangeProjectDescriptionHandler implements CommandHandlerInterface
{
    private ProjectRepositoryInterface $projectRepository;

    public function __construct(
        ProjectRepositoryInterface $projectRepository
    ) {
        $this->projectRepository = $projectRepository;
    }

    public function __invoke(ChangeProjectDescription $command): void
    {
        $project = $this->projectRepository->get($command->projectID);

        $project->changeProjectDescription($command->projectDescription);

        $this->projectRepository->store($project);
    }
}

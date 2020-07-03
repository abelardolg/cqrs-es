<?php

declare(strict_types=1);

namespace App\Domain\Project\Repository;

use App\Domain\Project\Project;
use Ramsey\Uuid\UuidInterface;

interface ProjectRepositoryInterface
{
    public function get(UuidInterface $projectID): Project;

    public function store(Project $project): void;
}

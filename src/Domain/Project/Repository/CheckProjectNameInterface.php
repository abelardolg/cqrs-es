<?php

declare(strict_types=1);

namespace App\Domain\Project\Repository;

use App\Domain\Project\ValueObject\ProjectName;
use Ramsey\Uuid\UuidInterface;

interface CheckProjectNameInterface
{
    public function existsProjectName(ProjectName $projectName): ?UuidInterface;
}

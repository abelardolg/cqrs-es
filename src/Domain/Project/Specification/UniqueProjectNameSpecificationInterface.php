<?php

declare(strict_types=1);

namespace App\Domain\Project\Specification;

use App\Domain\Project\Exception\ProjectNameAlreadyExistException;
use App\Domain\Project\ValueObject\ProjectName;

interface UniqueProjectNameSpecificationInterface
{
    /**
     * @throws ProjectNameAlreadyExistException
     */
    public function isUnique(ProjectName $projectName): bool;
}

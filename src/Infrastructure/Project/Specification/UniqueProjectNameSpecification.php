<?php

declare(strict_types=1);

namespace App\Infrastructure\Project\Specification;

use App\Domain\Project\Exception\ProjectNameAlreadyExistException;
use App\Domain\Project\Repository\CheckProjectNameInterface;
use App\Domain\Project\Specification\UniqueProjectNameSpecificationInterface;
use App\Domain\Project\ValueObject\ProjectName;
use App\Domain\Shared\Specification\AbstractSpecification;

final class UniqueProjectNameSpecification extends AbstractSpecification implements UniqueProjectNameSpecificationInterface
{
    private CheckProjectNameInterface $checkProjectByProjectName;

    public function __construct(CheckProjectNameInterface $checkProjectByProjectName)
    {
        $this->checkProjectByProjectName= $checkProjectByProjectName;
    }

    /**
     * @throws ProjectNameAlreadyExistException
     */
    public function isUnique(ProjectName $projectName): bool
    {
        return $this->isSatisfiedBy($projectName);
    }

    /**
     * @param ProjectName $value
     * @psalm-suppress MoreSpecificImplementedParamType
     */
    public function isSatisfiedBy($value): bool
    {
        if ($this->checkProjectByProjectName->existsProjectName($value)) {
            throw new ProjectNameAlreadyExistException();
        }

        return true;
    }
}

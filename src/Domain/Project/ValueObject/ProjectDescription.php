<?php

declare(strict_types=1);

namespace App\Domain\Project\ValueObject;

use Assert\Assertion;

class ProjectDescription
{
    public const MIN_LENGTH = 6;
    public const MAX_LENGTH = 50;
    private string $projectDescription;

    private function __construct(string $projectDescription)
    {
        $this->projectDescription = $projectDescription;
    }

    /**
     * @throws \Assert\AssertionFailedException
     */
    public static function fromString(string $projectDescription): self
    {
        Assertion::nullOrBetweenLength($projectDescription, self::MIN_LENGTH, self::MAX_LENGTH, 'No es una descripción válida para un proyecto.');

        return new self($projectDescription);
    }

    public function __toString(): string
    {
        return $this->projectDescription;
    }
    public function toString(): string
    {
        return $this->projectDescription;
    }
}

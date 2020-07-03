<?php

declare(strict_types=1);

namespace App\Domain\Project\ValueObject;

use Assert\Assertion;

class ProjectName
{
    public const MIN_LENGTH = 6;
    public const MAX_LENGTH = 20;
    private string $projectName;

    private function __construct(string $projectName)
    {
        $this->projectName = $projectName;
    }

    /**
     * @throws \Assert\AssertionFailedException
     */
    public static function fromString(string $projectName): self
    {
        Assertion::nullOrBetweenLength($projectName, self::MIN_LENGTH, self::MAX_LENGTH, 'No es un nombre de proyecto válido');

        return new self($projectName);
    }

    public function toString(): string
    {
        return $this->projectName;
    }

    public function __toString(): string
    {
        return $this->projectName;
    }
}

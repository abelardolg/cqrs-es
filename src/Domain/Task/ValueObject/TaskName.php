<?php

declare(strict_types=1);

namespace App\Domain\Task\ValueObject;

use Assert\Assertion;

class TaskName
{
    public const MIN_LENGTH = 6;
    public const MAX_LENGTH = 20;
    private string $taskName;

    private function __construct(string $taskName)
    {
        $this->taskName = $taskName;
    }

    /**
     * @throws \Assert\AssertionFailedException
     */
    public static function fromString(string $taskName): self
    {
        Assertion::nullOrBetweenLength($taskName, self::MIN_LENGTH, self::MAX_LENGTH, 'No es un nombre de tarea válido');

        return new self($taskName);
    }

    public function toString(): string
    {
        return $this->taskName;
    }

    public function __toString(): string
    {
        return $this->taskName;
    }
}

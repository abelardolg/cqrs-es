<?php

declare(strict_types=1);

namespace App\Domain\Task\ValueObject;

use Assert\Assertion;

class TaskDescription
{
    public const MIN_LENGTH = 6;
    public const MAX_LENGTH = 50;
    private string $taskDescription;

    private function __construct(string $taskDescription)
    {
        $this->taskDescription = $taskDescription;
    }

    /**
     * @throws \Assert\AssertionFailedException
     */
    public static function fromString(string $taskDescription): self
    {
        Assertion::nullOrBetweenLength($taskDescription, self::MIN_LENGTH, self::MAX_LENGTH, 'No es una descripción válida para una tarea.');

        return new self($taskDescription);
    }

    public function __toString(): string
    {
        return $this->taskDescription;
    }
    public function toString(): string
    {
        return $this->taskDescription;
    }
}

<?php

declare(strict_types=1);

namespace App\Domain\User\ValueObject;

use Assert\Assertion;

class UserName
{
    private const MIN_LENGTH = 6;
    private const MAX_LENGTH = 15;

    private string $name;

    private function __construct(string $name)
    {
        $this->name= $name;
    }

    /**
     * @throws \Assert\AssertionFailedException
     */
    public static function fromString(string $name): self
    {
        Assertion::notNull($name, 'No es un nombre válido');
        Assertion::minLength($name, self::MIN_LENGTH, sprintf('El nombre tiene que tener como mínimo %s letras', self::MIN_LENGTH));
        Assertion::minLength($name, self::MIN_LENGTH, sprintf('El nombre tiene que tener como máximo %s letras', self::MAX_LENGTH));

        return new self($name);
    }

    public function toString(): string
    {
        return $this->name;
    }

    public function __toString(): string
    {
        return $this->name;
    }
}

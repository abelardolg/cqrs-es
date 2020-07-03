<?php

declare(strict_types=1);

namespace App\Domain\Shared\ValueObject;

final class IsActive
{
    private bool $isActive;

    private function __construct(bool $isActive)
    {
        $this->isActive = $isActive;
    }

    public static function fromString(bool $isActive): self
    {
        return new self($isActive);
    }

    public function toString(): string
    {
        return $this->isActive ? "true" : "false";
    }

    public function __toString(): string
    {
        return $this->isActive ? "true" : "false";
    }
}

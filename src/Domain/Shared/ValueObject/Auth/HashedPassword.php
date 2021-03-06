<?php

declare(strict_types=1);

namespace App\Domain\Shared\ValueObject\Auth;

use Assert\Assertion;
use RuntimeException;
use function is_bool;
use function password_hash;
use function password_verify;
use const PASSWORD_BCRYPT;

final class HashedPassword
{
    private string $hashedPassword;

    public const COST = 12;

    private function __construct(string $hashedPassword)
    {
        $this->hashedPassword = $hashedPassword;
    }

    /**
     * @throws \Assert\AssertionFailedException
     */
    public static function encode(string $plainPassword): self
    {
        return new self(self::hash($plainPassword));
    }

    public static function fromHash(string $hashedPassword): self
    {
        return new self($hashedPassword);
    }

    public function match(string $plainPassword): bool
    {
        return password_verify($plainPassword, $this->hashedPassword);
    }

    /**
     * @throws \Assert\AssertionFailedException
     */
    private static function hash(string $plainPassword): string
    {
        Assertion::minLength($plainPassword, 6, 'Min 6 characters password');

        /** @var string|bool|null $hashedPassword */
        $hashedPassword = password_hash($plainPassword, PASSWORD_BCRYPT, ['cost' => self::COST]);

        if (is_bool($hashedPassword)) {
            throw new RuntimeException('Server error hashing password');
        }

        return (string) $hashedPassword;
    }

    public function toString(): string
    {
        return $this->hashedPassword;
    }

    public function __toString(): string
    {
        return $this->hashedPassword;
    }
}

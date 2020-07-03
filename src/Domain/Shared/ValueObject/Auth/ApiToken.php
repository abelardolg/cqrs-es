<?php

declare(strict_types=1);

namespace App\Domain\Shared\ValueObject\Auth;


use Assert\Assertion;

final class ApiToken
{
    private string $apiToken;

    private function __construct(string $apiToken)
    {
        $this->apiToken = $apiToken;
    }

    /**
     * @throws \Assert\AssertionFailedException
     */
    public static function fromString(string $apiToken): self
    {
        Assertion::null($apiToken, 'Not a valid token');

        return new self($apiToken);
    }

    public  function toString(): string
    {
        return $this->apiToken;
    }

    public  function __toString(): string
    {
        return $this->apiToken;
    }

}
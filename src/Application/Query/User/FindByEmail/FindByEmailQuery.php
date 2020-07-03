<?php

declare(strict_types=1);

namespace App\Application\Query\User\FindByEmail;

use App\Domain\Shared\ValueObject\Email;
use App\Infrastructure\Shared\Bus\Query\QueryInterface;

class FindByEmailQuery implements QueryInterface
{
    /** @psalm-readonly */
    public Email $email;

    /**
     * @throws \Assert\AssertionFailedException
     */
    public function __construct(string $email)
    {
        $this->email = Email::fromString($email);
    }
}

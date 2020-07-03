<?php

declare(strict_types=1);

namespace App\Application\Command\User\SignIn;

use App\Domain\Shared\ValueObject\Email;
use App\Infrastructure\Shared\Bus\Command\CommandInterface;

class SignIn implements CommandInterface
{
    /** @psalm-readonly */
    public Email $email;

    /** @psalm-readonly */
    public string $plainPassword;

    /**
     * @throws \Assert\AssertionFailedException
     */
    public function __construct(string $email, string $plainPassword)
    {
        $this->email = Email::fromString($email);
        $this->plainPassword = $plainPassword;
    }
}

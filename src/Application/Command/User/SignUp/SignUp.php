<?php

declare(strict_types=1);

namespace App\Application\Command\User\SignUp;

use App\Domain\Shared\ValueObject\Auth\Credentials;
use App\Domain\Shared\ValueObject\Auth\HashedPassword;
use App\Domain\Shared\ValueObject\Email;
use App\Domain\User\ValueObject\UserName;
use App\Infrastructure\Shared\Bus\Command\CommandInterface;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class SignUp implements CommandInterface
{
    /** @psalm-readonly */
    public UuidInterface $uuid;

    /** @psalm-readonly */
    public Credentials $credentials;

    /** @psalm-readonly */
    public UserName $userName;

    /**
     * @throws \Assert\AssertionFailedException
     */
    public function __construct(string $uuid, string $email, string $plainPassword, string $userName)
    {
        $this->uuid = Uuid::fromString($uuid);
        $this->credentials = new Credentials(Email::fromString($email), HashedPassword::encode($plainPassword));
        $this->userName = UserName::fromString($userName);
    }
}

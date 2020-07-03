<?php

declare(strict_types=1);

namespace App\Domain\User\Event;

use App\Domain\Shared\ValueObject\DateTime;
use App\Domain\Shared\ValueObject\Auth\Credentials;
use App\Domain\Shared\ValueObject\Auth\HashedPassword;
use App\Domain\Shared\ValueObject\Email;
use App\Domain\User\ValueObject\UserName;
use Assert\Assertion;
use Broadway\Serializer\Serializable;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

final class UserWasCreated implements Serializable
{


    /**
     * @throws \App\Domain\Shared\Exception\DateTimeException
     * @throws \Assert\AssertionFailedException
     */
    public static function deserialize(array $data): self
    {
        Assertion::keyExists($data, 'userID');
        Assertion::keyExists($data, 'credentials');
        Assertion::keyExists($data, 'created_at');
        Assertion::keyExists($data, 'name');

        return new self(
            Uuid::fromString($data['userID'])
            , new Credentials(
                Email::fromString($data['credentials']['email']),
                HashedPassword::fromHash($data['credentials']['password'])
            )
            , DateTime::fromString($data['created_at'])
            , UserName::fromString($data['name'])
        );
    }

    public function serialize(): array
    {
        return [
            'userID' => $this->userID->toString()
            , 'credentials' => [
                'email' => $this->credentials->email->toString(),
                'password' => $this->credentials->password->toString(),
            ]
            , 'created_at' => $this->createdAt->toString()
            , 'name' => $this->name->toString()
        ];
    }

    public function __construct(UuidInterface $userID, Credentials $credentials, DateTime $createdAt, UserName $name)
    {
        $this->userID = $userID;
        $this->credentials = $credentials;
        $this->createdAt = $createdAt;
        $this->name = $name;
    }

    /** @var UuidInterface */
    public $userID;

    /** @var Credentials */
    public $credentials;

    /** @var DateTime */
    public $createdAt;

    /** @var Username */
    public $name;
}

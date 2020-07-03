<?php

declare(strict_types=1);

namespace App\Infrastructure\User\Query\Projections;

use App\Domain\Shared\ValueObject\DateTime;
use App\Domain\Shared\ValueObject\Auth\Credentials;
use App\Domain\Shared\ValueObject\Auth\HashedPassword;
use App\Domain\Shared\ValueObject\Email;
use App\Domain\User\ValueObject\UserName;
use Broadway\ReadModel\SerializableReadModel;
use Broadway\Serializer\Serializable;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

/**
 * @psalm-suppress MissingConstructor
 */
class UserView implements SerializableReadModel
{
    private UuidInterface $userID;

    private Credentials $credentials;

    private DateTime$createdAt;

    private ?DateTime $updatedAt;

//    private UserName $userName;
    private string $name;

    private function __construct(
        UuidInterface $userID
        , Credentials $credentials
        , DateTime $createdAt
        , ?DateTime $updatedAt
        , string $name
    ) {
        $this->userID = $userID;
        $this->credentials = $credentials;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
        $this->name = $name;
    }

    /**
     * @throws \App\Domain\Shared\Exception\DateTimeException
     * @throws \Assert\AssertionFailedException
     */
    public static function fromSerializable(Serializable $event): self
    {
        return self::deserialize($event->serialize());
    }

    /**
     * @throws \App\Domain\Shared\Exception\DateTimeException
     * @throws \Assert\AssertionFailedException
     *
     * @return UserView
     */
    public static function deserialize(array $data): self
    {
        return new self(
            Uuid::fromString($data['userID'])
            , new Credentials(
                Email::fromString($data['credentials']['email']),
                HashedPassword::fromHash($data['credentials']['password'] ?? '')
            )
            , DateTime::fromString($data['created_at'])
            , isset($data['updated_at']) ? DateTime::fromString($data['updated_at']) : null
//            , UserName::fromString($data['userName'])
            , $data['name']
        );
    }

    public function serialize(): array
    {
        return [
            'userID' => $this->getId()
            , 'credentials' => [
                'email' => (string) $this->credentials->email,
            ]
            , 'created_at' => $this->updatedAt
            , 'updated_at' => $this->createdAt
            , 'name' => $this->name
        ];
    }

    public function userID(): UuidInterface
    {
        return $this->userID;
    }
    public function name(): string
    {
        return (string) $this->name;
    }
    public function email(): string
    {
        return (string) $this->credentials->email;
    }
    public function changeEmail(Email $email): void
    {
        $this->credentials->email = $email;
    }
    public function changeUpdatedAt(DateTime $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }
    public function hashedPassword(): string
    {
        return (string) $this->credentials->password;
    }
    public function getId(): string
    {
        return $this->userID->toString();
    }
}

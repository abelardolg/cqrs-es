<?php

declare(strict_types=1);

namespace App\Domain\User;

use App\Domain\Shared\Exception\DateTimeException;
use App\Domain\Shared\ValueObject\Auth\Credentials;
use App\Domain\Shared\ValueObject\Auth\HashedPassword;
use App\Domain\Shared\ValueObject\DateTime;
use App\Domain\Shared\ValueObject\Email;
use App\Domain\User\ValueObject\UserName;

use App\Domain\User\Event\UserEmailChanged;
use App\Domain\User\Event\UserSignedIn;
use App\Domain\User\Event\UserWasCreated;
use App\Domain\User\Exception\InvalidCredentialsException;
use App\Domain\User\Specification\UniqueEmailSpecificationInterface;
use Assert\Assertion;
use Broadway\EventSourcing\EventSourcedAggregateRoot;
use Ramsey\Uuid\UuidInterface;

/**
 * @psalm-suppress MissingConstructor
 */
class User extends EventSourcedAggregateRoot
{
    /** @var UuidInterface */
    private $userID;

    /** @var string */
    private $name;

    /** @var Email */
    private $email;

    /** @var HashedPassword */
    private $hashedPassword;

    /** @var DateTime */
    private $createdAt;

    /** @var DateTime|null */
    private $updatedAt;

    /**
     * @throws DateTimeException
     */
    public static function create(
        UuidInterface $userID
        , Credentials $credentials
        , UniqueEmailSpecificationInterface $uniqueEmailSpecification
        , UserName $name
    ): self {
        $uniqueEmailSpecification->isUnique($credentials->email);

        $user = new self();

        $user->apply(new UserWasCreated($userID, $credentials, DateTime::now(), $name));

        return $user;
    }

    /**
     * @throws DateTimeException
     */
    public function changeEmail(
        Email $email,
        UniqueEmailSpecificationInterface $uniqueEmailSpecification
    ): void {
        $uniqueEmailSpecification->isUnique($email);
        $this->apply(new UserEmailChanged($this->userID, $email, DateTime::now()));
    }

    /**
     * @throws InvalidCredentialsException
     */
    public function signIn(string $plainPassword): void
    {
        if (!$this->hashedPassword->match($plainPassword)) {
            throw new InvalidCredentialsException('Se ha introducido credenciales no válidas.');
        }

        $this->apply(new UserSignedIn($this->userID, $this->email));
    }

    protected function applyUserWasCreated(UserWasCreated $event): void
    {
        $this->userID = $event->userID;

        $this->setEmail($event->credentials->email);
        $this->setHashedPassword($event->credentials->password);
        $this->setCreatedAt($event->createdAt);
        $this->setUsername($event->name);
    }

    /**
     * @throws \Assert\AssertionFailedException
     */
    protected function applyUserEmailChanged(UserEmailChanged $event): void
    {
        Assertion::notEq($this->email->toString(), $event->email->toString(), 'New email should be different');

        $this->setEmail($event->email);
        $this->setUpdatedAt($event->updatedAt);
    }

    private function setEmail(Email $email): void
    {
        $this->email = $email;
    }

    private function setHashedPassword(HashedPassword $hashedPassword): void
    {
        $this->hashedPassword = $hashedPassword;
    }

    private function setCreatedAt(DateTime $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    private function setUpdatedAt(DateTime $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }

    private function setUserName(UserName $name): void
    {
        $this->name= $name;
    }

    public function createdAt(): string
    {
        return $this->createdAt->toString();
    }

    public function userName(): string
    {
        return $this->name;
    }

    public function updatedAt(): ?string
    {
        return isset($this->updatedAt) ? $this->updatedAt->toString() : null;
    }

    public function email(): string
    {
        return $this->email->toString();
    }

    public function uuid(): string
    {
        return $this->userID->toString();
    }

    public function getAggregateRootId(): string
    {
        return $this->userID->toString();
    }
}

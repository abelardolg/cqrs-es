<?php

declare(strict_types=1);

namespace App\Application\Command\User\ChangeEmail;

use App\Domain\User\Repository\UserRepositoryInterface;
use App\Domain\User\Specification\UniqueEmailSpecificationInterface;
use App\Infrastructure\Shared\Bus\Command\CommandHandlerInterface;

class ChangeEmailHandler implements CommandHandlerInterface
{
    private UserRepositoryInterface $userRepository;

    private UniqueEmailSpecificationInterface $uniqueEmailSpecification;

    public function __construct(
        UserRepositoryInterface $userRepository,
        UniqueEmailSpecificationInterface $uniqueEmailSpecification
    ) {
        $this->userRepository = $userRepository;
        $this->uniqueEmailSpecification = $uniqueEmailSpecification;
    }

    public function __invoke(ChangeEmail $command): void
    {
        $user = $this->userRepository->get($command->userUuid);

        $user->changeEmail($command->email, $this->uniqueEmailSpecification);

        $this->userRepository->store($user);
    }
}

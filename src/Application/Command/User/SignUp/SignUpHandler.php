<?php

declare(strict_types=1);

namespace App\Application\Command\User\SignUp;

use App\Domain\User\Repository\UserRepositoryInterface;
use App\Domain\User\Specification\UniqueEmailSpecificationInterface;
use App\Domain\User\User;
use App\Infrastructure\Shared\Bus\Command\CommandHandlerInterface;

class SignUpHandler implements CommandHandlerInterface
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

    /**
     * @throws \App\Domain\Shared\Exception\DateTimeException
     */
    public function __invoke(SignUp $command): void
    {
        $user = User::create($command->uuid, $command->credentials, $this->uniqueEmailSpecification, $command->userName);

        $this->userRepository->store($user);
    }
}

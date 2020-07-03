<?php

declare(strict_types=1);

namespace App\Application\Query\Auth\GetToken;

use App\Domain\User\Repository\GetUserCredentialsByEmailInterface;
use App\Infrastructure\Shared\Bus\Query\QueryHandlerInterface;
use App\Infrastructure\User\Auth\AuthenticationProvider;

class GetTokenHandler implements QueryHandlerInterface
{
    private GetUserCredentialsByEmailInterface $userCredentialsByEmail;

    private AuthenticationProvider $authenticationProvider;

    public function __construct(
        GetUserCredentialsByEmailInterface $userCredentialsByEmail,
        AuthenticationProvider $authenticationProvider
    ) {
        $this->authenticationProvider = $authenticationProvider;
        $this->userCredentialsByEmail = $userCredentialsByEmail;
    }

    /**
     * @throws \Assert\AssertionFailedException
     */
    public function __invoke(GetTokenQuery $query): string
    {
        [$uuid, $email, $hashedPassword, $username] = $this->userCredentialsByEmail->getCredentialsByEmail($query->email);

        return $this->authenticationProvider->generateToken($uuid, $email, $hashedPassword, $username);
    }
}

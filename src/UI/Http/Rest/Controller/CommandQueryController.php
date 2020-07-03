<?php

declare(strict_types=1);

namespace App\UI\Http\Rest\Controller;

use App\Infrastructure\Shared\Bus\Command\CommandBus;
use App\Infrastructure\Shared\Bus\Command\CommandInterface;
use App\Infrastructure\Shared\Bus\Query\QueryBus;
use App\Infrastructure\User\Auth\Session;
use App\UI\Http\Rest\Response\JsonApiFormatter;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

abstract class CommandQueryController extends QueryController
{
    private CommandBus $commandBus;
    /**
     * @var Session
     */
    private Session $session;

    public function __construct(
        CommandBus $commandBus
        , QueryBus $queryBus
        , JsonApiFormatter $formatter
        , UrlGeneratorInterface $router
        , Session $session
    ) {
        parent::__construct($queryBus, $formatter, $router);

        $this->commandBus = $commandBus;
        $this->session = $session;
    }

    /**
     * @throws \Throwable
     */
    protected function exec(CommandInterface $command): void
    {
        $this->commandBus->handle($command);
    }

    /**
     * @returns [ 'uuid': string, 'username': string, 'roles': array<string>]
     * @throws App\Domain\User\Exception\InvalidCredentialsException
     */
    protected function userFromSession(): array
    {
        return $this->session->get();
    }
}

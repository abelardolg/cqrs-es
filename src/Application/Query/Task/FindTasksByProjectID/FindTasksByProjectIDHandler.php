<?php

declare(strict_types=1);

namespace App\Application\Query\Task\FindTasksByProjectID;

use App\Infrastructure\Shared\Bus\Query\QueryHandlerInterface;
use App\Infrastructure\Task\Query\Mysql\MysqlTaskReadModelRepository;

class FindTasksByProjectIDHandler implements QueryHandlerInterface
{
    private MysqlTaskReadModelRepository $repository;

    public function __construct(MysqlTaskReadModelRepository $repository)
    {
        $this->repository = $repository;
    }
    /**
     * @throws \App\Domain\Shared\Query\Exception\NotFoundException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function __invoke(FindTasksByProjectIDQuery $query): array
    {
        return $this->repository->findTasksByProjectID($query->projectID);
    }
}

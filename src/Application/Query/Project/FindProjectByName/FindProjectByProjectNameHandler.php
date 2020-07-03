<?php

declare(strict_types=1);

namespace App\Application\Query\Project\FindProjectByName;

use App\Infrastructure\Shared\Bus\Query\Item;
use App\Infrastructure\Shared\Bus\Query\QueryHandlerInterface;
use App\Infrastructure\Project\Query\Mysql\MysqlProjectReadModelRepository;
use App\Infrastructure\Project\Query\Projections\ProjectView;

class FindProjectByProjectNameHandler implements QueryHandlerInterface
{
    private MysqlProjectReadModelRepository $repository;

    public function __construct(MysqlProjectReadModelRepository $repository)
    {
        $this->repository = $repository;
    }
    /**
     * @throws \App\Domain\Shared\Query\Exception\NotFoundException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function __invoke(FindProjectByName $query): Item
    {
        /** @var ProjectView $projectView */
        $projectView= $this->repository->findProjectByName($query->projectName);

        return new Item($projectView);
    }
}

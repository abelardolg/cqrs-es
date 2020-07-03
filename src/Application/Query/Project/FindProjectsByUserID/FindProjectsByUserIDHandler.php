<?php

declare(strict_types=1);

namespace App\Application\Query\Project\FindProjectsByUserID;

use App\Infrastructure\Shared\Bus\Query\Item;
use App\Infrastructure\Shared\Bus\Query\QueryHandlerInterface;
use App\Infrastructure\Project\Query\Mysql\MysqlProjectReadModelRepository;

class FindProjectsByUserIDHandler implements QueryHandlerInterface
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
    public function __invoke(FindProjectsByUserID $query): array
    {
        /** @var array $projectIDs */
        $projectIDs = $this->repository->findProjectsByUserID($query->userID);
        $projectViews = [];
        foreach($projectIDs as $projectID) {
            $projectViews[] = new Item($projectID);
        }
        return $projectViews;
    }
}

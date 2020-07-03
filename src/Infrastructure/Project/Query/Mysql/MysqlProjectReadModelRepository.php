<?php

declare(strict_types=1);

namespace App\Infrastructure\Project\Query\Mysql;

use App\Domain\Project\Repository\CheckProjectNameInterface;
use App\Domain\Project\ValueObject\ProjectName;
use App\Infrastructure\Shared\Query\Repository\MysqlRepository;
use App\Infrastructure\Project\Query\Projections\ProjectView;
use Doctrine\ORM\AbstractQuery;
use Ramsey\Uuid\UuidInterface;

final class MysqlProjectReadModelRepository extends MysqlRepository implements CheckProjectNameInterface
{
    private const PROJECT_TABLE = "projects";

    protected function getClass(): string
    {
        return ProjectView::class;
    }

    /**
     * @throws \App\Domain\Shared\Query\Exception\NotFoundException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function oneByUuid(UuidInterface $projectID): ProjectView
    {
        $qb = $this->repository
            ->createQueryBuilder(self::PROJECT_TABLE)
            ->where(self::PROJECT_TABLE . '.projectID = :projectID')
            ->setParameter('projectID', $projectID->getBytes())
        ;

        return $this->oneOrException($qb);
    }

    /**
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findProjectByName(ProjectName $projectName): ?UuidInterface
    {
        $projectID= $this->repository
            ->createQueryBuilder(self::PROJECT_TABLE)
            ->select(self::PROJECT_TABLE . '.projectID')
            ->where(self::PROJECT_TABLE . '.projectName = :projectName')
            ->setParameter('projectName', $projectName)
            ->getQuery()
            ->setHydrationMode(AbstractQuery::HYDRATE_ARRAY)
            ->getOneOrNullResult()
        ;

        return $projectID['project_id'] ?? null;
    }
    public function findProjectsByUserID(UuidInterface $userID): ?UuidInterface
    {
        $projectIDs = $this->repository
            ->createQueryBuilder(self::PROJECT_TABLE)
            ->where(self::PROJECT_TABLE . '.userID = :userID')
            ->setParameter('userID', $userID)
            ->getQuery()
            ->setHydrationMode(AbstractQuery::HYDRATE_ARRAY)
            ->getArrayResult()
        ;

        return $projectIDs['projectID'] ?? null;
    }

    public function add(ProjectView $projectRead): void
    {
        $this->register($projectRead);
    }

    public function existsProjectName(ProjectName $projectName): ?UuidInterface
    {
        $userId = $this->repository
            ->createQueryBuilder(self::PROJECT_TABLE)
            ->select(self::PROJECT_TABLE . '.projectID')
            ->where(self::PROJECT_TABLE . '.projectName = :projectName')
            ->setParameter('projectName', $projectName)
            ->getQuery()
            ->setHydrationMode(AbstractQuery::HYDRATE_ARRAY)
            ->getOneOrNullResult()
        ;

        return $userId['projectID'] ?? null;
    }
}

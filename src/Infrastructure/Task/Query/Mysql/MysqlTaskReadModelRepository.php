<?php

declare(strict_types=1);

namespace App\Infrastructure\Task\Query\Mysql;

use App\Domain\Task\Repository\CheckTaskNameInterface;
use App\Domain\Task\ValueObject\TaskName;
use App\Infrastructure\Shared\Query\Repository\MysqlRepository;
use App\Infrastructure\Task\Query\Projections\TaskView;
use Doctrine\ORM\AbstractQuery;
use Ramsey\Uuid\UuidInterface;

final class MysqlTaskReadModelRepository extends MysqlRepository implements CheckTaskNameInterface
{
    private const TASK_TABLE = "tasks";

    public function findTasksByProjectID(UuidInterface $projectID)
    {
        $taskID = $this->repository
            ->createQueryBuilder(self::TASK_TABLE)
            ->select(self::TASK_TABLE . '.task_id')
            ->where(self::TASK_TABLE . '.project_id = :projectID')
            ->setParameter('projectID', (string) $projectID)
            ->getQuery()
            ->setHydrationMode(AbstractQuery::HYDRATE_ARRAY)
            ->getArrayResult()
        ;

        return $taskID['task_id'] ?? null;
    }

    protected function getClass(): string
    {
        return TaskView::class;
    }

    /**
     * @throws \App\Domain\Shared\Query\Exception\NotFoundException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function oneByUuid(UuidInterface $uuid): TaskView
    {
        $qb = $this->repository
            ->createQueryBuilder(self::TASK_TABLE)
            ->where(self::TASK_TABLE . '.task_id = :taskID')
            ->setParameter('taskID', $uuid->getBytes())
        ;

        return $this->oneOrException($qb);
    }

    /**
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function existsTaskName(TaskName $taskName): ?UuidInterface
    {
        $taskID = $this->repository
            ->createQueryBuilder(self::TASK_TABLE)
            ->select(self::TASK_TABLE . '.task_id')
            ->where(self::TASK_TABLE . '.task_name = :taskName')
            ->setParameter('taskName', (string) $taskName)
            ->getQuery()
            ->setHydrationMode(AbstractQuery::HYDRATE_ARRAY)
            ->getOneOrNullResult()
        ;

        return $taskID['task_id'] ?? null;
    }

    /**
     * @throws \App\Domain\Shared\Query\Exception\NotFoundException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function oneByTaskName(TaskName $taskName): TaskView
    {
        $qb = $this->repository
            ->createQueryBuilder(self::TASK_TABLE)
            ->where(self::TASK_TABLE . '.task_name = :taskName')
            ->setParameter('taskName', $taskName->toString())
        ;

        return $this->oneOrException($qb);
    }

    public function add(TaskView $taskRead): void
    {
        $this->register($taskRead);
    }
}

<?php

declare(strict_types=1);

namespace App\Infrastructure\Task\Query;

use App\Domain\Task\Event\TaskWasCreated;
use App\Infrastructure\Task\Query\Mysql\MysqlTaskReadModelRepository;
use App\Infrastructure\Task\Query\Projections\TaskView;
use Broadway\ReadModel\Projector;

class TaskProjectionFactory extends Projector
{
    private MysqlTaskReadModelRepository $repository;

    public function __construct(MysqlTaskReadModelRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @throws \Assert\AssertionFailedException
     */
    protected function applyTaskWasCreated(TaskWasCreated $taskWasCreated): void
    {
        $taskReadModel = TaskView::fromSerializable($taskWasCreated);

        $this->repository->add($taskReadModel);
    }
}

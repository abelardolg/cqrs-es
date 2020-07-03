<?php

declare(strict_types=1);

namespace App\Domain\Task\Repository;

use App\Domain\Task\Task;
use Ramsey\Uuid\UuidInterface;

interface TaskRepositoryInterface
{
    public function get(UuidInterface $uuid): Task;

    public function store(Task $task): void;
}

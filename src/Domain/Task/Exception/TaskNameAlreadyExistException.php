<?php

declare(strict_types=1);

namespace App\Domain\Task\Exception;

use InvalidArgumentException;

class TaskNameAlreadyExistException extends InvalidArgumentException
{
    public function __construct()
    {
        parent::__construct('El nombre de la tarea ya existe.');
    }
}

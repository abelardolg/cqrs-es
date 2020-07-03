<?php

declare(strict_types=1);

namespace App\Domain\Project\Exception;

use InvalidArgumentException;

class ProjectNameAlreadyExistException extends InvalidArgumentException
{
    public function __construct()
    {
        parent::__construct('Nombre de proyecto ya registrado.');
    }
}

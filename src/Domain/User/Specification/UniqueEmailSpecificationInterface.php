<?php

declare(strict_types=1);

namespace App\Domain\User\Specification;

use App\Domain\User\Exception\EmailAlreadyExistException;
use App\Domain\Shared\ValueObject\Email;

interface UniqueEmailSpecificationInterface
{
    /**
     * @throws EmailAlreadyExistException
     */
    public function isUnique(Email $email): bool;
}

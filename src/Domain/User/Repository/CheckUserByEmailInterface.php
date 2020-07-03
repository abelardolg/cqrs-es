<?php

declare(strict_types=1);

namespace App\Domain\User\Repository;

use App\Domain\Shared\ValueObject\Email;
use Ramsey\Uuid\UuidInterface;

interface CheckUserByEmailInterface
{
    public function existsEmail(Email $email): ?UuidInterface;
}

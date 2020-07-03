<?php

declare(strict_types=1);

namespace App\Application\Query\Project\FindProjectByName;

use App\Domain\Project\ValueObject\ProjectName;
use App\Infrastructure\Shared\Bus\Query\QueryInterface;

class FindProjectByName implements QueryInterface
{
    /** @psalm-readonly */
    /** @var ProjectName*/
    public $projectName;

    /**
     * @throws \Assert\AssertionFailedException
     */
    public function __construct(string $projectName)
    {
        $this->projectName = $projectName;
    }
}

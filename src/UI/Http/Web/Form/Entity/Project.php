<?php

declare(strict_types=1);

namespace App\UI\Http\Web\Form\Entity;

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

final class Project
{
    private string $uuid;
    private string $projectName;
    private string $projectDescription;

    public function __construct()
    {
        $this->uuid = Uuid::uuid4()->toString();
        $this->projectName = "";
        $this->projectDescription = "";
    }

    /**
     * @return string
     */
    public function getUuid(): string
    {
        return $this->uuid;
    }

    /**
     * @param string $uuid
     */
    public function setUuid(string $uuid): void
    {
        $this->uuid = $uuid;
    }



    /**
     * @return string
     */
    public function getprojectName(): string
    {
        return $this->projectName;
    }

    /**
     * @param string $name
     */
    public function setProjectName(string $projectName): void
    {
        $this->projectName = $projectName;
    }

    /**
     * @return string
     */
    public function getProjectDescription(): string
    {
        return $this->projectDescription;
    }

    /**
     * @param string $description
     */
    public function setProjectDescription(string $projectDescription): void
    {
        $this->projectDescription = $projectDescription;
    }




}
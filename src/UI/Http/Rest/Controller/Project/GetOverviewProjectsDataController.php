<?php

declare(strict_types=1);

namespace App\UI\Http\Rest\Controller\Project;

use App\Application\Query\Project\FindOverviewProjectsData\FindProjectsByUserIDQuery;
use App\Infrastructure\Shared\Bus\Query\Item;
use App\UI\Http\Rest\Controller\QueryController;
use Assert\Assertion;
use Nelmio\ApiDocBundle\Annotation\Security;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

final class GetOverviewProjectsDataController extends QueryController
{
    public const LAST_OVERVIEW_PROJECTS_DATA = 5;
    /**
     * @Route(
     *     "/projects/overview",
     *     name="get_overviewprojectss_data",
     *     methods={"GET"}
     * )
     * @SWG\Response(
     *     response=200,
     *     description="Returns all the last updated projects"
     * )
     * @SWG\Response(
     *     response=400,
     *     description="Bad request"
     * )
     * @SWG\Response(
     *     response=404,
     *     description="Not found"
     * )
     * @SWG\Parameter(
     *     name="lastOverviewProjectsData",
     *     in="path",
     *     type="string",
     *     description="Number of queried last projects to retrieve all their data"
     * )
     *
     * @SWG\Tag(name="Project")
     *
     * @Security(name="Bearer")
     *
     * @throws \Assert\AssertionFailedException
     * @throws \Throwable
     */
    public function __invoke(string $lastOverviewProjectsData = self::LAST_OVERVIEW_PROJECTS_DATA): JsonResponse
    {
        Assertion::lessOrEqualThan(0, $lastOverviewProjectsData, "El número de proyectos a consultar tiene que ser al menos 1");

        $query = new FindProjectsByUserIDQuery($lastOverviewProjectsData);

        /** @var Item $project */
        $projects = $this->ask($query);

        return $this->json($projects);
    }
}

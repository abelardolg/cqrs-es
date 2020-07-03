<?php

declare(strict_types=1);

namespace App\UI\Http\Rest\Controller\Project;

use App\Application\Query\Project\FindProjectsByUserID\FindTasksByProjectIDQuery;
use App\UI\Http\Rest\Controller\QueryController;
use Assert\Assertion;
use Nelmio\ApiDocBundle\Annotation\Security;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

final class GetProjectsByUserController extends QueryController
{
    /**
     * @Route(
     *     "/projects/{userID}",
     *     name="get_projects_userID",
     *     methods={"GET"}
     * )
     * @SWG\Response(
     *     response=200,
     *     description="Returns the projects assocated of the given user ID"
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
     *     name="userID",
     *     in="path",
     *     type="string",
     *     description="email"
     * )
     *
     * @SWG\Tag(name="Project")
     *
     * @Security(name="Bearer")
     *
     * @throws \Assert\AssertionFailedException
     * @throws \Throwable
     */
    public function __invoke(string $userID): JsonResponse
    {
        Assertion::notNull($userID, "El id del usuario no puede ser vacío");

        $query = new FindTasksByProjectIDQuery($userID);

        /** @var array $project */
        $projects = $this->ask($query);

        return $this->json($projects);
    }
}

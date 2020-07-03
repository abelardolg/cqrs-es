<?php

declare(strict_types=1);

namespace App\UI\Http\Rest\Controller\Project;

use App\Application\Command\Project\CreateProject\CreateProject;
use App\UI\Http\Web\Controller\AbstractRenderController;
use Assert\Assertion;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

final class CreateProjectPostController extends AbstractRenderController
{
    /**
     * @Route(
     *     "/project/create",
     *     name="api_project_create",
     *     methods={"POST"}
     * )
     *
     * @SWG\Response(
     *     response=201,
     *     description="Project created successfully"
     * )
     * @SWG\Response(
     *     response=400,
     *     description="Bad request"
     * )
     * @SWG\Response(
     *     response=409,
     *     description="Conflict"
     * )
     * @SWG\Parameter(
     *     name="project",
     *     type="object",
     *     in="body",
     *     schema=@SWG\Schema(type="object",
     *         @SWG\Property(property="uuid", type="string"),
     *         @SWG\Property(property="email", type="string"),
     *         @SWG\Property(property="password", type="string"),
     *         @SWG\Property(property="projectName", type="string"),
     *         @SWG\Property(property="projectDescription", type="string")
     *     )
     * )
     *
     * @SWG\Tag(name="Project")
     * @throws \Assert\AssertionFailedException
     */
    public function __invoke(Request $request): JsonResponse
    {
        $uuid = $request->get('uuid');
        $projectName = $request->get('projectName');
        $projectDescription = $request->get('projectDescription');
//        Assertion::notNull($uuid, "Uuid no puede ser nulo");
//        Assertion::notNull($projectName, "El proyecto debe tener un nombre");
//        Assertion::notNull($projectDescription, "El proyecto debe contener una descripción");

        $command = new CreateProject($uuid, $projectName, $projectDescription);

        $this->exec($command);

        return new JsonResponse(null, JsonResponse::HTTP_CREATED);
    }
}

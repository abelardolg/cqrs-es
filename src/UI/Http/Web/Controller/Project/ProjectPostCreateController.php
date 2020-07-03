<?php

declare(strict_types=1);

namespace App\UI\Http\Web\Controller\Project;

use App\Application\Command\Project\CreateProject\CreateProject;
use App\Application\Query\User\FindByEmail\FindByEmailQuery;
use App\Domain\Project\Exception\ProjectNameAlreadyExistException;
use App\Infrastructure\Shared\Bus\Command\CommandBus;
use App\Infrastructure\User\Auth\Session;
use App\UI\Http\Web\Controller\AbstractRenderController;
use App\UI\Http\Web\Form\Entity\Project;
use App\UI\Http\Web\Form\Type\ProjectType;
use http\Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProjectPostCreateController extends AbstractRenderController
{
    /**
     * @Route(
     *     "/project/create",
     *     name="project_create",
     *     methods={"GET", "POST"}
     * )
     *
     */
    public function new(Request $request, Session $session): Response
    {
        $project = new Project();
        $form = $this->createForm(ProjectType::class, $project
        , [
            "action" => $this->generateUrl("project_create")
            ,"method" => "POST"
            ]
        )
        ;
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            try{
                $this->post($request, $session);
                return $this->redirectToRoute("project_create");
                } catch(Exception | ProjectNameAlreadyExistException $projectNameAlreadyExistException) {
                    return $this->render('/profile/crud_project/create.html.twig',
                        [
                            'form' =>$form->createView(),
                            "error" =>$projectNameAlreadyExistException->getMessage()
                        ]);
            }
        }
        return $this->render('/profile/crud_project/create.html.twig',
            [
                'form' =>$form->createView(),
            ]);
    }

    /**
     * @Route(
     *     "/project/data",
     *     name="project_data",
     *     methods={"POST"}
     * )
     */
    private function post(Request $request, Session $session): JsonResponse
    {
        if ($request->request->has('project')) {
            $userID = $session->get()["userID"]->toString();
            $project =$request->request->get('project');
            $uuid = $project["uuid"];
            $projectName =$project["projectName"];
            $projectDescription =$project["projectDescription"];

            $this->exec(new CreateProject($uuid, $projectName, $projectDescription, $userID));

                $response = new JSONResponse(
                    [
                        "Proyecto creado satisfactoriamente"
                    ]
                    , Response::HTTP_OK
                );
                $response->headers->set('Content-Type', 'application/json');
        } else {
            $response = new JSONResponse(
                [
                    "No se han proporcionado los datos necesarios para la petición"
                ]
                , Response::HTTP_BAD_REQUEST
            )
            ;
        }

        return $response;
    }

//    private function post1(Request $request): Response
//    {
//        $projectName = "projectName";
//        $projectDescription = "projectDescription";
//
//        $request->request->set("projectName", $projectName);
//        $request->request->set("projectDescription", $projectDescription);
//
//        $response =  $this->redirectToRoute(
//            "project_data"
//            , [
//                "request" => $request
//            ]
//            ,307);
//        ;
//        return $response;
//    }
}

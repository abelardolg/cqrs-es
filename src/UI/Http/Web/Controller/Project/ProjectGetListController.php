<?php

declare(strict_types=1);

namespace App\UI\Http\Web\Controller\Project;

use App\Application\Command\Project\CreateProject\CreateProject;
use App\Application\Query\Project\FindProjectsByUserID\FindProjectsByUserID;
use App\Application\Query\User\FindByEmail\FindByEmailQuery;
use App\Domain\Project\Exception\ProjectNameAlreadyExistException;
use App\Infrastructure\Shared\Bus\Command\CommandBus;
use App\Infrastructure\User\Auth\Session;
use App\UI\Http\Web\Controller\AbstractRenderController;
use App\UI\Http\Web\Form\Entity\Project;
use App\UI\Http\Web\Form\Type\ProjectType;
use http\Exception;
use Ramsey\Uuid\Uuid;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProjectGetListController extends AbstractRenderController
{

    /**
     * @Route(
     *     "/project/list/{email}",
     *     name="project_list",
     *     methods={"GET"}
     * )
     *
     */
    public function list(string $email): Response
    {
        $username = $this->ask(new FindByEmailQuery($email));
        $list = $this->ask(new FindProjectsByUserID($username->id));

//
//
//        } else {
//            $response = new JSONResponse(
//                [
//                    "No se han proporcionado los datos necesarios para la petición"
//                ]
//                , Response::HTTP_BAD_REQUEST
//            )
//            ;
//        }

//        return $response;
    }

    private function post1(Request $request): Response
    {
        $projectName = "projectName";
        $projectDescription = "projectDescription";

        $request->request->set("projectName", $projectName);
        $request->request->set("projectDescription", $projectDescription);

        $response =  $this->redirectToRoute(
            "project_data"
            , [
                "request" => $request
            ]
            ,307);
        ;
        return $response;
    }
}

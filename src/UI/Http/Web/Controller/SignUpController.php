<?php

declare(strict_types=1);

namespace App\UI\Http\Web\Controller;

use App\Application\Command\User\SignUp\SignUp;
use App\Domain\User\Exception\EmailAlreadyExistException;
use Assert\Assertion;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SignUpController extends AbstractRenderController
{
    /**
     * @Route(
     *     "/sign-up",
     *     name="sign-up",
     *     methods={"GET"}
     * )
     *
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function getSignup(): Response
    {
        return $this->renderAbstract('/signup/register.html.twig');
    }

    /**
     * @Route(
     *     "/sign-up",
     *     name="sign-up-post",
     *     methods={"POST"}
     * )
     *
     * @throws \Assert\AssertionFailedException
     * @throws \Throwable
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function post(Request $request): Response
    {
        $email = $request->request->get('_email');
        $password = $request->request->get('_password');
        $userName = $request->request->get('nombre');
        $uuid = Uuid::uuid4()->toString();
        try {
            Assertion::notNull($email, 'Debe proporcionar un email');
            Assertion::notNull($password, 'Debe proporcionar una contraseña');
            Assertion::notNull($userName, 'Debe proporcionar un nombre de usuario');
            $this->exec(new SignUp($uuid, $email, $password, $userName));
            return $this->renderAbstract('signin/login.html.twig', ['uuid' => $uuid, 'email' => $email]);
        } catch (EmailAlreadyExistException $exception) {
            return $this->renderAbstract('signup/register.html.twig', ['error' => 'El email proporcionado ya existe.'], Response::HTTP_CONFLICT);
        } catch (\InvalidArgumentException $exception) {
            return $this->renderAbstract('signup/register.html.twig', ['error' => $exception->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }
}

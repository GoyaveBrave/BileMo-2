<?php

namespace App\Controller;

use App\Responder\Interfaces\JsonResponderInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\User;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Swagger\Annotations as SWG;

class AuthController extends AbstractController
{
    public function register(Request $request, UserPasswordEncoderInterface $encoder, JsonResponderInterface $responder)
    {
        $em = $this->getDoctrine()->getManager();

        $username = $request->request->get('_username');
        $password = $request->request->get('_password');

        $user = new User();
        $user->setUsername($username);
        $user->setPassword($encoder->encodePassword($user, $password));
        $em->persist($user);
        $em->flush();

        $data = [
            'succes' => [
                'code' => Response::HTTP_CREATED,
                'message' => 'User '.$this->getUser()->getUsername().' successfully created',
            ],
        ];

        return $responder($request, $data, Response::HTTP_CREATED, ['content-Type' => 'application/json']);
    }

    /**
     * @SWG\Response(
     *     response=200,
     *     description="return a success message",
     *     examples={"succes": {"code": 200, "message": "Logged in as [username]"}},
     * )
     *
     * @SWG\Tag(name="Authentification")
     *
     * @param JsonResponderInterface $responder
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function api(JsonResponderInterface $responder, Request $request)
    {
        $data = [
            'succes' => [
                'code' => Response::HTTP_OK,
                'message' => 'Logged in as '.$this->getUser()->getUsername(),
            ],
        ];

        return $responder($request, $data, Response::HTTP_OK, ['content-Type' => 'application/json']);
    }
}

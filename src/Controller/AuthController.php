<?php

namespace App\Controller;

use App\Exceptions\BadRequestException;
use App\Responder\Interfaces\JsonResponderInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\User;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class AuthController extends AbstractController
{
    /**
     * @param Request                $request
     * @param JsonResponderInterface $responder
     * @param ValidatorInterface     $validator
     *
     * @return JsonResponse
     *
     * @throws BadRequestException
     */
    public function register(Request $request, JsonResponderInterface $responder, ValidatorInterface $validator)
    {
        $em = $this->getDoctrine()->getManager();

        $username = $request->request->get('_username');
        $password = $request->request->get('_password');

        $user = new User($username, $password);

        $errors = $validator->validate($user);

        if (count($errors)) {
            throw new BadRequestException((string) $errors);
        }

        $em->persist($user);
        $em->flush();

        $data = [
            'success' => [
                'code' => Response::HTTP_CREATED,
                'message' => "L'utilisateur ".$user->getUsername().' a bien été créé',
            ],
        ];

        return $responder($request, $data, Response::HTTP_CREATED, ['content-Type' => 'application/json']);
    }

    /**
     * @param JsonResponderInterface $responder
     * @param Request                $request
     *
     * @return JsonResponse
     */
    public function api(JsonResponderInterface $responder, Request $request)
    {
        $data = [
            'success' => [
                'code' => Response::HTTP_OK,
                'message' => 'Connecté en tant que '.$this->getUser()->getUsername(),
            ],
        ];

        return $responder($request, $data, Response::HTTP_OK, ['content-Type' => 'application/json']);
    }
}

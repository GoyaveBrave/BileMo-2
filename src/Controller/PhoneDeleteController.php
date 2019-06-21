<?php

namespace App\Controller;

use App\Entity\Phone;
use App\Exceptions\NotFoundException;
use App\Responder\Interfaces\JsonResponderInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PhoneDeleteController extends AbstractController
{
    /**
     * Delete a phone.
     *
     * @Route("/api/phone/delete/{id}", name="phone_delete", methods={"DELETE"})
     *
     * @param Request                $request
     * @param JsonResponderInterface $responder
     *
     * @return Response
     *
     * @throws NotFoundException
     */
    public function deletePhone(Request $request, JsonResponderInterface $responder)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $manager = $this->getDoctrine()->getManager();
        $phone = $manager->getRepository(Phone::class)->find($request->attributes->get('id'));

        if (null === $phone) {
            throw new NotFoundException("le portable n'existe pas.");
        }

        $httpCode = Response::HTTP_OK;
        $data = [
            'success' => [
                'code' => $httpCode,
                'message' => 'le portable '.$phone->getName().' a été supprimé.',
            ],
        ];

        $manager->remove($phone);
        $manager->flush();

        return $responder($request, $data, $httpCode, ['content-Type' => 'application/json']);
    }
}

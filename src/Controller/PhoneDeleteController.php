<?php

namespace App\Controller;

use App\Entity\Phone;
use App\Exceptions\NotFoundException;
use App\Responder\Interfaces\JsonResponderInterface;
use Nelmio\ApiDocBundle\Annotation\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Swagger\Annotations as SWG;

class PhoneDeleteController extends AbstractController
{
    /**
     * Delete a phone.
     *
     * @Route("/api/phone/delete/{id}", name="phone_delete", methods={"DELETE"})
     *
     * @SWG\Response(
     *     response=200,
     *     description="The phone has been deleted",
     *     examples={"succes": {"code": 200, "message": "le portable [phone_name] a été supprimé."}},
     * )
     *
     * @SWG\Response(
     *     response=404,
     *     description="The phone has been not found",
     *     examples={"error": {"code": 404, "message": "le portable n'existe pas."}},
     * )
     *
     * @SWG\Tag(name="Phone")
     * @Security(name="Bearer")
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
        $em = $this->getDoctrine()->getManager();
        $phone = $em->getRepository(Phone::class)->find($request->attributes->get('id'));

        if (is_null($phone)) {
            throw new NotFoundException("le portable n'existe pas.");
        }

        $httpCode = Response::HTTP_OK;
        $data = [
            'succes' => [
                'code' => $httpCode,
                'message' => 'le portable '.$phone->getName().' a été supprimé.',
            ],
        ];

        $em->remove($phone);
        $em->flush();

        return $responder($request, $data, $httpCode, ['content-Type' => 'application/json']);
    }
}

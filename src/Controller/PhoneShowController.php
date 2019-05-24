<?php

namespace App\Controller;

use App\Entity\Phone;
use App\Responder\Interfaces\JsonResponderInterface;
use Nelmio\ApiDocBundle\Annotation\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Nelmio\ApiDocBundle\Annotation\Model;
use Swagger\Annotations as SWG;

class PhoneShowController extends AbstractController
{
    /**
     * Get a phone by ID.
     *
     * @Route("/api/phone/{id}", name="phone_show", methods={"GET"})
     *
     * @SWG\Response(
     *     response=200,
     *     description="The requested phone has been found",
     *     @Model(type=Phone::class)
     * )
     *
     * @SWG\Response(
     *     response=404,
     *     description="The requested phone has been not found",
     *     examples={"error": {"code": 404, "message": "le portable n'existe pas."}},
     * )
     *
     * @SWG\Tag(name="Phone")
     * @Security(name="Bearer")
     *
     * @param Request                $request
     * @param JsonResponderInterface $responder
     *
     * @return JsonResponse
     */
    public function getPhone(Request $request, JsonResponderInterface $responder)
    {
        $em = $this->getDoctrine()->getManager();
        $data = $em->getRepository(Phone::class)->find($request->attributes->get('id'));
        $lastModified = null;

        if ($data) {
            $lastModified = $data->getUpdatedAt();
            $httpCode = Response::HTTP_OK;
        } else {
            $httpCode = Response::HTTP_NOT_FOUND;
            $data = [
                'error' => [
                    'code' => $httpCode,
                    'message' => "le portable n'existe pas.",
                ],
            ];
        }

        return $responder($request, $data, $httpCode, ['Content-Type' => 'application/json'], $lastModified);
    }
}

<?php

namespace App\Controller;

use App\Entity\Phone;
use App\Exceptions\NotFoundException;
use App\Loader\PhoneLoader;
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
     * @param PhoneLoader            $phoneLoader
     *
     * @return JsonResponse
     *
     * @throws NotFoundException
     */
    public function getPhone(Request $request, JsonResponderInterface $responder, PhoneLoader $phoneLoader)
    {
        /** @var Phone $phone */
        $phone = $this->getDoctrine()->getRepository(Phone::class)->find($request->attributes->get('id'));

        if (is_null($phone)) {
            throw new NotFoundException("le portable n'existe pas.");
        }

        $lastModified = $phone->getUpdatedAt();
        $data = $phoneLoader->load($phone);

        return $responder($request, $data, Response::HTTP_OK, ['Content-Type' => 'application/json'], $lastModified);
    }
}

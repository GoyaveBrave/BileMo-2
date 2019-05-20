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
        $phone = $em->getRepository(Phone::class)->find($request->attributes->get('id'));
        $lastModified = $phone->getUpdatedAt();

        return $responder($request, $phone, Response::HTTP_OK, ['Content-Type' => 'application/json'], $lastModified);
    }
}

<?php

namespace App\Controller;

use App\Entity\Phone;
use App\Exceptions\NotFoundException;
use App\Loader\PhoneLoader;
use App\Responder\Interfaces\JsonResponderInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PhoneShowController extends AbstractController
{
    /**
     * Get a phone by ID.
     *
     * @Route("/api/phone/{id}", name="phone_show", methods={"GET"})
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

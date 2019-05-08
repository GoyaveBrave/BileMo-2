<?php

namespace App\Controller;

use App\Entity\Phone;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PhoneShowController extends AbstractController
{
    /**
     * @Route("/phone/{id}", name="phone_show")
     * @param Phone $phone
     * @return JsonResponse
     */
    public function getPhone(Phone $phone)
    {
        return $this->json($phone, Response::HTTP_OK, ['Content-Type' => 'application/json']);
    }
}

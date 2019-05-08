<?php

namespace App\Controller;

use App\Entity\Phone;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PhoneListController extends AbstractController
{
    /**
     * @Route("/phone/list", name="phone_list", methods={"GET"})
     *
     * @return Response
     */
    public function GetAllPhones()
    {
        $em = $this->getDoctrine()->getManager();
        $phones = $em->getRepository(Phone::class)->findAll();

        return $this->json($phones, 200, ['Content-Type' => 'application/json']);
    }
}

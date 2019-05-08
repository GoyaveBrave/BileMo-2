<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class PhoneListController extends AbstractController
{
    /**
     * @Route("/phone/list", name="phone_list")
     * @param Request $request
     * @param SerializerInterface $serializer
     * @return JsonResponse
     */
    public function index(Request $request, SerializerInterface $serializer)
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/PhoneListController.php',
        ]);
    }
}

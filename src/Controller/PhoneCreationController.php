<?php

namespace App\Controller;

use App\Entity\Phone;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class PhoneCreationController extends AbstractController
{
    /**
     * @Route("/phone/creation", name="phone_creation")
     *
     * @param Request             $request
     * @param SerializerInterface $serializer
     *
     * @return Response
     */
    public function createPhone(Request $request, SerializerInterface $serializer)
    {
        $em = $this->getDoctrine()->getManager();
        $data = $request->getContent();
        $phone = $serializer->deserialize($data, Phone::class, 'json');

        // TODO: Verification
        $em->persist($phone);
        $em->flush();

        return new Response('', Response::HTTP_CREATED);
    }
}

<?php

namespace App\Controller;

use App\Entity\Phone;
use Nelmio\ApiDocBundle\Annotation\Model;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Swagger\Annotations as SWG;

class PhoneCreationController extends AbstractController
{
    /**
     * Create a phone entity.
     *
     * @Route("/phone/creation", name="phone_creation", methods={"POST"})
     *
     * @SWG\Response(
     *     response=201,
     *     description="The phone has been created",
     * )
     *
     * @SWG\Parameter(
     *     name="phone",
     *     in="body",
     *     description="The information of the phone",
     *     type="json",
     *     @SWG\Schema(ref=@Model(type=Phone::class))
     * )
     *
     * @SWG\Tag(name="phones")
     *
     * @param Request             $request
     * @param SerializerInterface $serializer
     *
     * @return Response
     */
    public function createPhone(Request $request, SerializerInterface $serializer)
    {
        $em = $this->getDoctrine()->getManager();
        $requestContent = $request->getContent();
        $phone = $serializer->deserialize($requestContent, Phone::class, 'json');

        // TODO: Verification
        $em->persist($phone);
        $em->flush();

        $data = [
            'succes' => [
                'code' => Response::HTTP_CREATED,
                'message' => 'le portable '.$phone->getName().' a été ajouté.',
            ],
        ];

        return $this->json($data, Response::HTTP_CREATED, ['content-Type' => 'application/json']);
    }
}

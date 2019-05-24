<?php

namespace App\Controller;

use App\Entity\Phone;
use App\Exceptions\BadRequestException;
use App\Responder\Interfaces\JsonResponderInterface;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Swagger\Annotations as SWG;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class PhoneCreationController extends AbstractController
{
    /**
     * Create a phone entity.
     *
     * @Route("/api/phone/creation", name="phone_creation", methods={"POST"})
     *
     * @SWG\Response(
     *     response=201,
     *     description="The phone has been created",
     *     examples={"succes": {"code": 201, "message": "le portable [phone_name] a été ajouté."}},
     * )
     *
     * @SWG\Response(
     *     response=400,
     *     description="The phone could not be created",
     *     examples={"error": {"code": 400, "message": "le portable n'a pas pu être créé."}},
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
     * @SWG\Tag(name="Phone")
     * @Security(name="Bearer")
     *
     * @param Request                $request
     * @param SerializerInterface    $serializer
     * @param JsonResponderInterface $responder
     * @param ValidatorInterface     $validator
     *
     * @return Response
     *
     * @throws BadRequestException
     */
    public function createPhone(Request $request, SerializerInterface $serializer, JsonResponderInterface $responder, ValidatorInterface $validator)
    {
        $em = $this->getDoctrine()->getManager();
        $requestContent = $request->getContent();
        $phone = $serializer->deserialize($requestContent, Phone::class, 'json');
        $errors = $validator->validate($phone);

        if (count($errors)) {
            $errorsWithNoReturn = preg_replace('~[\r\n]+~', '', (string) $errors);
            $errorsMessage = preg_replace('/\s+/', ' ', $errorsWithNoReturn);
            throw new BadRequestException($errorsMessage);

        }

        $em->persist($phone);
        $em->flush();

        $data = [
            'succes' => [
                'code' => Response::HTTP_CREATED,
                'message' => 'le portable '.$phone->getName().' a été ajouté.',
            ],
        ];

        return $responder($request, $data, Response::HTTP_CREATED, ['content-Type' => 'application/json']);
    }
}

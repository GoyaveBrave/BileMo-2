<?php

namespace App\Controller;

use App\DTO\PhoneDTO;
use App\Entity\Phone;
use App\Exceptions\BadRequestException;
use App\Responder\Interfaces\JsonResponderInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class PhoneCreationController extends AbstractController
{
    /**
     * Create a phone entity.
     *
     * @Route("/api/phone/creation", name="phone_creation", methods={"POST"})
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
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $manager = $this->getDoctrine()->getManager();
        $requestContent = $request->getContent();
        /** @var PhoneDTO $phoneDTO */
        $phoneDTO = $serializer->deserialize($requestContent, PhoneDTO::class, 'json');
        $phone = new Phone(
            $phoneDTO->getName(),
            $phoneDTO->getPrice(),
            $phoneDTO->getCamera(),
            $phoneDTO->getBattery(),
            $phoneDTO->getScreen(),
            $phoneDTO->getRam(),
            $phoneDTO->getMemory()
        );
        $errors = $validator->validate($phone);

        if (count($errors)) {
            $errorsWithNoReturn = preg_replace('~[\r\n]+~', '', (string) $errors);
            $errorsMessage = preg_replace('/\s+/', ' ', $errorsWithNoReturn);
            throw new BadRequestException($errorsMessage);

        }

        $manager->persist($phone);
        $manager->flush();

        $data = [
            'success' => [
                'code' => Response::HTTP_CREATED,
                'message' => 'le portable '.$phone->getName().' a été ajouté.',
            ],
        ];

        return $responder($request, $data, Response::HTTP_CREATED, ['content-Type' => 'application/json']);
    }
}

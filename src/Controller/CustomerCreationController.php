<?php

namespace App\Controller;

use App\DTO\CustomerDTO;
use App\Entity\Customer;
use App\Exceptions\BadRequestException;
use App\Responder\Interfaces\JsonResponderInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CustomerCreationController extends AbstractController
{
    /**
     * Create a Customer entity.
     *
     * @Route("/api/customer/creation", name="customer_creation", methods={"POST"})
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
    public function createCustomer(Request $request, SerializerInterface $serializer, JsonResponderInterface $responder, ValidatorInterface $validator)
    {
        $em = $this->getDoctrine()->getManager();
        $requestContent = $request->getContent();

        /** @var CustomerDTO $customerDTO */
        $customerDTO = $serializer->deserialize($requestContent, CustomerDTO::class, 'json');
        $customer = new Customer(
            $customerDTO->getFirstname(),
            $customerDTO->getLastname(),
            $customerDTO->getEmail(),
            $this->getUser()
        );

        $errors = $validator->validate($customer);

        if (count($errors)) {
            $errorsWithNoReturn = preg_replace('~[\r\n]+~', '', (string) $errors);
            $errorsMessage = preg_replace('/\s+/', ' ', $errorsWithNoReturn);
            throw new BadRequestException($errorsMessage);
        }

        $em->persist($customer);
        $em->flush();

        $data = [
            'success' => [
                'code' => Response::HTTP_CREATED,
                'message' => "l'utilisateur a été ajouté.",
            ],
        ];

        return $responder($request, $data, Response::HTTP_CREATED, ['Content-Type' => 'application/json']);
    }
}

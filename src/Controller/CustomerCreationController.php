<?php

namespace App\Controller;

use App\Entity\Customer;
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

class CustomerCreationController extends AbstractController
{
    /**
     * Create a Customer entity.
     *
     * @Route("/api/customer/creation", name="customer_creation", methods={"POST"})
     *
     * @SWG\Response(
     *     response=201,
     *     description="return a success message",
     *     examples={"succes": {"code": 201, "message": "l'utilisateur a été ajouté."}},
     * )
     *
     * @SWG\Response(
     *     response=400,
     *     description="The user could not be created",
     *     examples={"error": {"code": 400, "message": "l'utilisateure n'a pas pu être créé."}},
     * )
     *
     * @SWG\Parameter(
     *     name="customer",
     *     in="body",
     *     description="The information of the customer",
     *     type="json",
     *     @SWG\Schema(ref=@Model(type=Customer::class, groups={"insert"}))
     * )
     *
     * @SWG\Tag(name="Customer")
     * @Security(name="Bearer")
     *
     * @param Request                $request
     * @param SerializerInterface    $serializer
     * @param JsonResponderInterface $responder
     * @param ValidatorInterface     $validator
     *
     * @return Response
     */
    public function createCustomer(Request $request, SerializerInterface $serializer, JsonResponderInterface $responder, ValidatorInterface $validator)
    {
        $em = $this->getDoctrine()->getManager();
        $requestContent = $request->getContent();

        /** @var Customer $customer */
        $customer = $serializer->deserialize($requestContent, Customer::class, 'json');
        $customer->setUser($this->getUser());

        $errors = $validator->validate($customer);

        if (count($errors)) {
            $httpCode = Response::HTTP_BAD_REQUEST;
            $data = ['error' => $errors];
        } else {
            $em->persist($customer);
            $em->flush();

            $httpCode = Response::HTTP_CREATED;
            $data = [
                'succes' => [
                    'code' => $httpCode,
                    'message' => "l'utilisateur a été ajouté.",
                ],
            ];
        }

        return $responder($request, $data, $httpCode, ['content-Type' => 'application/json']);
    }
}

<?php

namespace App\Controller;

use App\Entity\Customer;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Swagger\Annotations as SWG;

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
     * @param Request             $request
     * @param SerializerInterface $serializer
     *
     * @return Response
     */
    public function createCustomer(Request $request, SerializerInterface $serializer)
    {
        $em = $this->getDoctrine()->getManager();
        $requestContent = $request->getContent();

        /** @var Customer $customer */
        $customer = $serializer->deserialize($requestContent, Customer::class, 'json');
        $customer->setUser($this->getUser());

        // TODO: Verification
        $em->persist($customer);
        $em->flush();

        $data = [
            'succes' => [
                'code' => Response::HTTP_CREATED,
                'message' => "l'utilisateur a été ajouté.",
            ],
        ];

        return $this->json($data, Response::HTTP_CREATED, ['content-Type' => 'application/json']);
    }
}

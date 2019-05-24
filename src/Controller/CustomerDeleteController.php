<?php

namespace App\Controller;

use App\Entity\Customer;
use App\Entity\User;
use App\Responder\Interfaces\JsonResponderInterface;
use Nelmio\ApiDocBundle\Annotation\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Swagger\Annotations as SWG;

class CustomerDeleteController extends AbstractController
{
    /**
     * Delete a customer.
     *
     * @Route("/api/customer/delete/{id}", name="customer_delete", methods={"DELETE"})
     *
     * @SWG\Response(
     *     response=200,
     *     description="The customer has been deleted",
     *     examples={"succes": {"code": 200, "message": "l'utilisateur a été supprimé."}},
     * )
     *
     * @SWG\Response(
     *     response=404,
     *     description="The customer has been not found",
     *     examples={"error": {"code": 404, "message": "l'utilisateur n'existe pas."}},
     * )
     *
     * @SWG\Tag(name="Customer")
     * @Security(name="Bearer")
     *
     * @param Request                $request
     * @param JsonResponderInterface $responder
     *
     * @return Response
     */
    public function deleteCustomer(Request $request, JsonResponderInterface $responder)
    {
        /** @var User $user */
        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();
        $customer = $em->getRepository(Customer::class)->findOneBy(['user' => $user, 'id' => $request->attributes->get('id')]);

        if ($customer) {
            $httpCode = Response::HTTP_OK;
            $data = [
                'succes' => [
                    'code' => $httpCode,
                    'message' => "l'utilisateur a été supprimé.",
                ],
            ];

            $em->remove($customer);
            $em->flush();

        } else {
            $httpCode = Response::HTTP_NOT_FOUND;
            $data = [
                'error' => [
                    'code' => $httpCode,
                    'message' => "l'utilisateur n'existe pas.",
                ],
            ];
        }

        return $responder($request, $data, Response::HTTP_OK, ['content-Type' => 'application/json']);
    }
}

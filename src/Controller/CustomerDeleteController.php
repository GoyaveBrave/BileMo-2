<?php

namespace App\Controller;

use App\Entity\Customer;
use App\Entity\User;
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
     *     description="The phone has been deleted",
     *     examples={"succes": {"code": 200, "message": "l'utilisateur a été supprimé."}},
     * )
     *
     * @SWG\Tag(name="Customer")
     * @Security(name="Bearer")
     *
     * @param Request $request
     * @return Response
     */
    public function deleteCustomer(Request $request)
    {
        /** @var User $user */
        $user = $this->getUser();
        $customer = $this->getDoctrine()->getRepository(Customer::class)->findOneBy(['user' => $user, 'id' => $request->attributes->get('id')]);

        $em = $this->getDoctrine()->getManager();
        // TODO: verification
        $em->remove($customer);
        $em->flush();

        $data = [
            'succes' => [
                'code' => Response::HTTP_OK,
                'message' => "l'utilisateur a été supprimé.",
            ],
        ];

        return $this->json($data, Response::HTTP_OK, ['content-Type' => 'application/json']);
    }
}

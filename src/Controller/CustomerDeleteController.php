<?php

namespace App\Controller;

use App\Entity\Customer;
use App\Entity\User;
use App\Exceptions\NotFoundException;
use App\Responder\Interfaces\JsonResponderInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CustomerDeleteController extends AbstractController
{
    /**
     * Delete a customer.
     *
     * @Route("/api/customer/delete/{id}", name="customer_delete", methods={"DELETE"})
     *
     * @param Request                $request
     * @param JsonResponderInterface $responder
     *
     * @return Response
     *
     * @throws NotFoundException
     */
    public function deleteCustomer(Request $request, JsonResponderInterface $responder)
    {
        $em = $this->getDoctrine()->getManager();
        $customer = $em->getRepository(Customer::class)->find($request->attributes->get('id'));

        if (is_null($customer)) {
            throw new NotFoundException("l'utilisateur n'existe pas.");
        }

        $this->denyAccessUnlessGranted('delete', $customer);

        $httpCode = Response::HTTP_OK;
        $data = [
            'succes' => [
                'code' => $httpCode,
                'message' => "l'utilisateur a été supprimé.",
            ],
        ];

        $em->remove($customer);
        $em->flush();

        return $responder($request, $data, Response::HTTP_OK, ['content-Type' => 'application/json']);
    }
}

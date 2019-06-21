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
        $manager = $this->getDoctrine()->getManager();
        $customer = $manager->getRepository(Customer::class)->find($request->attributes->get('id'));

        if (is_null($customer)) {
            throw new NotFoundException("l'utilisateur n'existe pas.");
        }

        $this->denyAccessUnlessGranted('delete', $customer);

        $httpCode = Response::HTTP_OK;
        $data = [
            'success' => [
                'code' => $httpCode,
                'message' => "l'utilisateur a été supprimé.",
            ],
        ];

        $manager->remove($customer);
        $manager->flush();

        return $responder($request, $data, Response::HTTP_OK, ['Content-Type' => 'application/json']);
    }
}

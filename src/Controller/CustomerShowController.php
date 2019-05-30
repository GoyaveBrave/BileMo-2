<?php

namespace App\Controller;

use App\Entity\Customer;
use App\Entity\User;
use App\Exceptions\NotFoundException;
use App\Loader\CustomerLoader;
use App\Responder\Interfaces\JsonResponderInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CustomerShowController extends AbstractController
{
    /**
     * Get a Customer by ID.
     *
     * @Route("/api/customer/{id}", name="customer_show", methods={"GET"})
     *
     * @param Request                $request
     * @param JsonResponderInterface $responder
     * @param CustomerLoader         $customerLoader
     *
     * @return JsonResponse
     *
     * @throws NotFoundException
     */
    public function getCustomer(Request $request, JsonResponderInterface $responder, CustomerLoader $customerLoader)
    {
        /** @var User $user */
        $user = $this->getUser();
        /** @var Customer $customer */
        $customer = $this->getDoctrine()->getRepository(Customer::class)->findOneBy(['user' => $user, 'id' => $request->attributes->get('id')]);

        if (is_null($customer)) {
            throw new NotFoundException("l'utilisateur n'existe pas.");
        }

        $lastModified = $customer->getUpdatedAt();
        $data = $customerLoader->load($customer);

        return $responder($request, $data, Response::HTTP_OK, ['Content-Type' => 'application/json'], $lastModified);
    }
}

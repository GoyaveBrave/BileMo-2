<?php

namespace App\Controller;

use App\Exceptions\NotFoundException;
use App\Loader\CustomerLoader;
use App\Repository\CustomerRepository;
use App\Responder\Interfaces\JsonResponderInterface;
use App\Service\LastModified;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CustomerListController extends AbstractController
{
    /**
     * List customers.
     *
     * @Route("/api/customer/list", name="customer_list", methods={"GET"})
     *
     * @param Request                $request
     * @param JsonResponderInterface $responder
     * @param CustomerRepository     $customerRepository
     * @param CustomerLoader         $customerLoader
     *
     * @return JsonResponse
     *
     * @throws NonUniqueResultException
     * @throws NotFoundException
     */
    public function getCustomersByUser(Request $request, JsonResponderInterface $responder, CustomerRepository $customerRepository, CustomerLoader $customerLoader)
    {
        $user = $this->getUser();
        $page = $request->get('page') ? $request->get('page') : 1;
        $maxResult = $request->get('results') ? $request->get('results') : 5;
        $totalPage = $customerRepository->findMaxNumberOfPage($user, $maxResult);

        if ($page > $totalPage || $page < 0) {
            throw new NotFoundException("La page n'existe pas");
        }

        $data = $customerLoader->loadAll($user, $page, $totalPage, $maxResult);
        $lastModified = LastModified::getLastModified($data);

        return $responder($request, $data, Response::HTTP_OK, ['Content-Type' => 'application/json'], $lastModified);
    }
}

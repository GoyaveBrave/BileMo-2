<?php

namespace App\Controller;

use App\Loader\CustomerLoader;
use App\Repository\CustomerRepository;
use App\Responder\Interfaces\JsonResponderInterface;
use App\Service\LastModified;
use App\Entity\Customer;
use Doctrine\ORM\NonUniqueResultException;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Swagger\Annotations as SWG;

class CustomerListController extends AbstractController
{
    /**
     * List customers.
     *
     * @SWG\Response(
     *     response=200,
     *     description="Return the list of customers of a user",
     *     @SWG\Schema(type="array", @SWG\Items(ref=@Model(type=Customer::class, groups={"display"})))
     * )
     * @SWG\Tag(name="Customer")
     * @Security(name="Bearer")
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
     */
    public function getCustomersByUser(Request $request, JsonResponderInterface $responder, CustomerRepository $customerRepository, CustomerLoader $customerLoader)
    {
        $user = $this->getUser();
        $page = $request->get('page') ? $request->get('page') : 1;
        $totalPage = $customerRepository->findMaxNumberOfPage($user);
        $lastModified = null;

        if ($page > $totalPage) {
            $httpCode = Response::HTTP_NOT_FOUND;
            $data = [
                'error' => [
                    'code' => $httpCode,
                    'message' => "La page n'existe pas",
                ],
            ];
        } else {
            $data = $customerLoader->loadAll($user, $page, $totalPage);
            $lastModified = LastModified::getLastModified($data);
            $httpCode = Response::HTTP_OK;
        }

        return $responder($request, $data, $httpCode, ['Content-Type' => 'application/json'], $lastModified);
    }
}

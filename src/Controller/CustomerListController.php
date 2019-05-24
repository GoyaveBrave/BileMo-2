<?php

namespace App\Controller;

use App\Repository\CustomerRepository;
use App\Responder\Interfaces\JsonResponderInterface;
use App\Service\LastModified;
use Exception;
use App\Entity\Customer;
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
     * @Route("/api/customer/list", name="custommer_list", methods={"GET"})
     *
     * @param Request                $request
     * @param JsonResponderInterface $responder
     * @param CustomerRepository     $customerRepository
     *
     * @return JsonResponse
     *
     * @throws Exception
     */
    public function getCustomersByUser(Request $request, JsonResponderInterface $responder, CustomerRepository $customerRepository)
    {
        $user = $this->getUser();
        $page = $request->get('page') ? $request->get('page') : 1;
        $totalPage = $customerRepository->findMaxNumberOfPage($user);
        $customers = $customerRepository->findByPage($page, $user);
        $lastModified = null;
        $context = [];

        if ($page > $totalPage) {
            $httpCode = Response::HTTP_NOT_FOUND;
            $data = [
                'error' => [
                    'code' => $httpCode,
                    'message' => "La page n'existe pas",
                ],
            ];
        } else {
            $lastModified = LastModified::getLastModified($customers);
            $context = ['groups' => 'display'];
            $httpCode = Response::HTTP_OK;
            $data = [
                'page' => $page.'/'.$totalPage,
                'data' => $customers,
            ];
        }

        return $responder($request, $data, $httpCode, ['Content-Type' => 'application/json'], $lastModified, $context);
    }
}

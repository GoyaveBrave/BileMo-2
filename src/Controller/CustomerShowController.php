<?php

namespace App\Controller;

use App\Entity\Customer;
use App\Entity\User;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Swagger\Annotations as SWG;
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
     * @SWG\Response(
     *     response=200,
     *     description="Return the requested customer",
     *     @Model(type=Customer::class, groups={"useful"})
     * )
     *
     * @SWG\Tag(name="Customer")
     * @Security(name="Bearer")
     *
     * @param int     $id
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function getCustomer(int $id, Request $request)
    {
        /** @var User $user */
        $user = $this->getUser();

        $customer = $this->getDoctrine()->getRepository(Customer::class)->findOneBy(['user' => $user, 'id' => $id]);
        $response = $this->json($customer, Response::HTTP_OK, ['Content-Type' => 'application/json'], ['groups' => 'useful']);

        $lastModified = $customer->getUpdatedAt();

        $response->setCache([
            'etag' => md5($response->getContent()),
            'last_modified' => $lastModified,
            'max_age' => 15,
            's_maxage' => 15,
            'public' => true,
        ]);

        $response->headers->addCacheControlDirective('must-revalidate', true);
        $response->expire();
        $response->isNotModified($request);

        return $response;
    }
}

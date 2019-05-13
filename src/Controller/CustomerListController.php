<?php

namespace App\Controller;

use DateTime;
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
     * Liste des utilisateurs d'un client.
     *
     * @SWG\Response(
     *     response=200,
     *     description="Return the list of customers of a user",
     *     @SWG\Schema(type="array", @SWG\Items(ref=@Model(type=Customer::class, groups={"useful"})))
     * )
     * @SWG\Tag(name="Customer")
     * @Security(name="Bearer")
     *
     * @Route("/api/customer/list", name="custommer_list", methods={"GET"})
     *
     * @param Request $request
     *
     * @return JsonResponse
     *
     * @throws Exception
     */
    public function getCustomersByUser(Request $request)
    {
        $user = $this->getUser();
        $customers = $user->getCustomers();
        $mostRecent = 0;

        /** @var Customer $customer */
        foreach ($customers as $customer) {
            $date = $customer->getUpdatedAt()->getTimestamp();
            if ($date > $mostRecent) {
                $mostRecent = $date;
            }
        }

        $lastModified = new DateTime();
        $lastModified->setTimestamp($mostRecent);

        $response = $this->json($customers, Response::HTTP_OK, ['Content-Type' => 'application/json'], ['groups' => 'useful']);

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

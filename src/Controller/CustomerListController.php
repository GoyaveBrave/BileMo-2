<?php

namespace App\Controller;

use App\Responder\Interfaces\JsonResponderInterface;
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
     *
     * @return JsonResponse
     *
     * @throws Exception
     */
    public function getCustomersByUser(Request $request, JsonResponderInterface $responder)
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

        $response = $responder($request, $customers, Response::HTTP_OK, ['Content-Type' => 'application/json'], $lastModified, ['groups' => 'display']);

        return $response;
    }
}

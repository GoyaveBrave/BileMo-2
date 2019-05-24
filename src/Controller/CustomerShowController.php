<?php

namespace App\Controller;

use App\Entity\Customer;
use App\Entity\User;
use App\Responder\Interfaces\JsonResponderInterface;
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
     *     @Model(type=Customer::class, groups={"display"})
     * )
     *
     * @SWG\Response(
     *     response=404,
     *     description="The requested customer has been not found",
     *     examples={"error": {"code": 404, "message": "l'utilisateur n'existe pas."}},
     * )
     *
     * @SWG\Tag(name="Customer")
     * @Security(name="Bearer")
     *
     * @param Request                $request
     * @param JsonResponderInterface $responder
     *
     * @return JsonResponse
     */
    public function getCustomer(Request $request, JsonResponderInterface $responder)
    {
        /** @var User $user */
        $user = $this->getUser();
        $data = $this->getDoctrine()->getRepository(Customer::class)->findOneBy(['user' => $user, 'id' => $request->attributes->get('id')]);
        $lastModified = null;
        $context = [];

        if ($data) {
            $lastModified = $data->getUpdatedAt();
            $context = ['groups' => 'display'];
            $httpCode = Response::HTTP_OK;
        } else {
            $httpCode = Response::HTTP_NOT_FOUND;
            $data = [
                'error' => [
                    'code' => $httpCode,
                    'message' => "l'utilisateur n'existe pas.",
                ],
            ];
        }

        return $responder($request, $data, $httpCode, ['Content-Type' => 'application/json'], $lastModified, $context);
    }
}

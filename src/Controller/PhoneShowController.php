<?php

namespace App\Controller;

use App\Entity\Phone;
use DateTime;
use Nelmio\ApiDocBundle\Annotation\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Nelmio\ApiDocBundle\Annotation\Model;
use Swagger\Annotations as SWG;

class PhoneShowController extends AbstractController
{
    /**
     * Get a phone by ID.
     *
     * @Route("/api/phone/{id}", name="phone_show", methods={"GET"})
     *
     * @SWG\Response(
     *     response=200,
     *     description="The requested phone has been found",
     *     @Model(type=Phone::class)
     * )
     *
     * @SWG\Tag(name="Phone")
     * @Security(name="Bearer")
     *
     * @param Phone   $phone
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function getPhone(Phone $phone, Request $request)
    {
        $response = $this->json($phone, Response::HTTP_OK, ['Content-Type' => 'application/json']);

        $lastModified = $phone->getUpdatedAt();

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

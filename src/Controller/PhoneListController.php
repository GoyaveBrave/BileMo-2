<?php

namespace App\Controller;

use App\Entity\Phone;
use DateTime;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Nelmio\ApiDocBundle\Annotation\Model;
use Swagger\Annotations as SWG;

class PhoneListController extends AbstractController
{
    /**
     * Get the list of phones.
     *
     * @Route("/api/phone/list", name="phone_list", methods={"GET"})
     * @SWG\Response(
     *     response=200,
     *     description="Get the list of phones",
     *     @SWG\Schema(type="array", @Model(type=Phone::class))
     * )
     * @SWG\Tag(name="phones")
     *
     * @param Request $request
     *
     * @return Response
     *
     * @throws Exception
     */
    public function getAllPhones(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $phones = $em->getRepository(Phone::class)->findAll();

        $mostRecent = 0;
        foreach ($phones as $phone) {
            $date = $phone->getUpdatedAt()->getTimestamp();
            if ($date > $mostRecent) {
                $mostRecent = $date;
            }
        }

        $lastModified = new DateTime();
        $lastModified->setTimestamp($mostRecent);

        $response = $this->json($phones, Response::HTTP_OK, ['Content-Type' => 'application/json']);

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

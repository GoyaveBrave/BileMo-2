<?php

namespace App\Controller;

use App\Entity\Phone;
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
     * @Route("/phone/list", name="phone_list", methods={"GET"})
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
     */
    public function getAllPhones(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $phones = $em->getRepository(Phone::class)->findAll();

        $response = $this->json($phones, Response::HTTP_OK, ['Content-Type' => 'application/json']);

        $response->setEtag(md5($response->getContent()));
        $response->setPublic(); // make sure the response is public/cacheable
        $response->isNotModified($request);

        return $response;
    }
}

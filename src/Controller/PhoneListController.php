<?php

namespace App\Controller;

use App\Entity\Phone;
use App\Responder\Interfaces\JsonResponderInterface;
use DateTime;
use Exception;
use Nelmio\ApiDocBundle\Annotation\Security;
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
     *     description="Return the list of phones",
     *     @SWG\Schema(type="array", @Model(type=Phone::class))
     * )
     * @SWG\Tag(name="Phone")
     * @Security(name="Bearer")
     *
     * @param Request                $request
     * @param JsonResponderInterface $responder
     *
     * @return Response
     *
     * @throws Exception
     */
    public function getAllPhones(Request $request, JsonResponderInterface $responder)
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

        return $responder($request, $phones, Response::HTTP_OK, ['Content-Type' => 'application/json'], $lastModified);
    }
}

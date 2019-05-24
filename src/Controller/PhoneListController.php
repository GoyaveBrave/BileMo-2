<?php

namespace App\Controller;

use App\Entity\Phone;
use App\Loader\PhoneLoader;
use App\Repository\PhoneRepository;
use App\Responder\Interfaces\JsonResponderInterface;
use App\Service\LastModified;
use Doctrine\ORM\NonUniqueResultException;
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
     *
     * @SWG\Response(
     *     response=200,
     *     description="Return the list of phones",
     *     @SWG\Schema(type="array", @Model(type=Phone::class))
     * )
     *
     * @SWG\Tag(name="Phone")
     * @Security(name="Bearer")
     *
     * @param Request                $request
     * @param JsonResponderInterface $responder
     * @param PhoneRepository        $phoneRepository
     * @param PhoneLoader            $phoneLoader
     *
     * @return Response
     *
     * @throws NonUniqueResultException
     */
    public function getAllPhones(Request $request, JsonResponderInterface $responder, PhoneRepository $phoneRepository, PhoneLoader $phoneLoader)
    {
        $page = $request->get('page') ? $request->get('page') : 1;
        $totalPage = $phoneRepository->findMaxNumberOfPage();
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
            $data = $phoneLoader->loadAll($page, $totalPage);
            $lastModified = LastModified::getLastModified($data);
            $httpCode = Response::HTTP_OK;
        }

        return $responder($request, $data, $httpCode, ['Content-Type' => 'application/json'], $lastModified);
    }
}

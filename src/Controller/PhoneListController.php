<?php

namespace App\Controller;

use App\Entity\Phone;
use App\Repository\PhoneRepository;
use App\Responder\Interfaces\JsonResponderInterface;
use App\Service\LastModified;
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
     *
     * @return Response
     *
     * @throws Exception
     */
    public function getAllPhones(Request $request, JsonResponderInterface $responder, PhoneRepository $phoneRepository)
    {
        $page = $request->get('page') ? $request->get('page') : 1;
        $phones = $phoneRepository->findByPage($page);
        $totalPage = $phoneRepository->findMaxNumberOfPage();

        if ($page > $totalPage) {
            $error = [
                'error' => [
                    'code' => Response::HTTP_NOT_FOUND,
                    'message' => "La page n'existe pas",
                ],
            ];

            return $responder($request, $error, Response::HTTP_NOT_FOUND, ['Content-Type' => 'application/json']);
        }

        $lastModified = LastModified::getLastModified($phones);

        $data = [
            'page' => $page.'/'.$totalPage,
            'data' => $phones,
        ];

        return $responder($request, $data, Response::HTTP_OK, ['Content-Type' => 'application/json'], $lastModified);
    }
}

<?php

namespace App\Controller;

use App\Exceptions\NotFoundException;
use App\Loader\PhoneLoader;
use App\Repository\PhoneRepository;
use App\Responder\Interfaces\JsonResponderInterface;
use App\Service\LastModified;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PhoneListController extends AbstractController
{
    /**
     * Get the list of phones.
     *
     * @Route("/api/phone/list", name="phone_list", methods={"GET"})
     *
     * @param Request                $request
     * @param JsonResponderInterface $responder
     * @param PhoneRepository        $phoneRepository
     * @param PhoneLoader            $phoneLoader
     *
     * @return Response
     *
     * @throws NonUniqueResultException
     * @throws NotFoundException
     */
    public function getAllPhones(Request $request, JsonResponderInterface $responder, PhoneRepository $phoneRepository, PhoneLoader $phoneLoader)
    {
        $page = $request->get('page') ? $request->get('page') : 1;
        $maxResult = $request->get('results') ? $request->get('results') : 5;
        $totalPage = $phoneRepository->findMaxNumberOfPage($maxResult);

        if ($page > $totalPage || $page < 0) {
            throw new NotFoundException("La page n'existe pas");
        }

        $data = $phoneLoader->loadAll($page, $totalPage, $maxResult);
        $lastModified = LastModified::getLastModified($data);

        return $responder($request, $data, Response::HTTP_OK, ['Content-Type' => 'application/json'], $lastModified);
    }
}

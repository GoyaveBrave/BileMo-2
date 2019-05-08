<?php

namespace App\Controller;

use App\Entity\Phone;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Swagger\Annotations as SWG;

class PhoneDeleteController extends AbstractController
{
    /**
     * @Route("/phone/delete/{id}", name="phone_delete", methods={"DELETE"})
     *
     * @SWG\Response(
     *     response=200,
     *     description="The phone has been deleted",
     * )
     *
     * @SWG\Tag(name="phones")
     *
     * @param Phone $phone
     *
     * @return Response
     */
    public function deletePhone(Phone $phone)
    {
        $em = $this->getDoctrine()->getManager();
        // TODO: verification
        $em->remove($phone);
        $em->flush();

        $data = [
            'succes' => [
                'code' => Response::HTTP_OK,
                'message' => 'le portable '.$phone->getName().' a été supprimé.',
            ],
        ];

        return $this->json($data, Response::HTTP_OK, ['content-Type' => 'application/json']);
    }
}

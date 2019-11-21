<?php

namespace App\Controller;

use App\Entity\Installation;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;


class InstallationController extends AbstractController
{

    public function __construct()
    {
        $encoders = [new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];

        $this->serializer = new Serializer($normalizers, $encoders);
    }

    private $serializer;

    /**
     * @Route("/api/installations", name="installations", methods={"GET"})
     */
    public function installations()
    {



        $repo = $this->getDoctrine()->getRepository(Installation::class);
        $installations = $repo->findAll();
        $response = $this->serializer->serialize($installations, 'json');

        return new JsonResponse($response, Response::HTTP_OK, ['Access-Control-Allow-Origin' => '*'], true);
    }
}

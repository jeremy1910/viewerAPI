<?php

namespace App\Controller;

use App\Entity\Badge;
use App\Entity\Profil;
use App\Entity\ProfilGlecteurVariable;

use App\Entity\Variable;
use App\Repository\ProfilRepository;
use App\Services\GlecteurDefService;
use App\Services\GlecteurService;
use App\Services\HandelParserService;
use App\Services\HandelTranslatePH;
use App\Services\Installation;
use App\Services\ProfilGlecteurVariableService;
use App\Services\ProfilService;
use App\Services\VariableService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\EncoderInterface;

class IndexController extends AbstractController
{

    public function __construct(HandelParserService $handelParserService, HandelTranslatePH $handelTranslatePH, EncoderInterface $encoder)
    {
        $this->handelParserService = $handelParserService;
        $this->handelTranslatePH = $handelTranslatePH;
        $this->encoder = $encoder;

    }

    private $handelParserService;
    private $handelTranslatePH;
    private $encoder;

    /**
     * @Route("/", name="index")
     */
    public function index()
    {
        //$this->handelParserService->parseFiles();
        //$this->handelTranslatePH->parsePH();


        return $this->render('index/index.html.twig', [
            'controller_name' => 'IndexController',
        ]);
    }

    /**
     * @return Response
     * @Route("/test", name="test")
     */
    public function test(){
        return $this->render('index/index.html.twig');
    }


    /**
     * @Route("/addInstallation", name="addInstallation", methods={"POST"})
     */
    public function addInstallation(Installation $installation, Request $request){
        set_time_limit(0);
        try{
            $installation->init()->addInstallation($request);
            $message = $this->encoder->encode(["success" => true, "message" => "Installation réussite"], 'json');
        }catch (\Exception $e){
            $message = $this->encoder->encode(["success" => false, "message" => $e->getMessage()], 'json');
        }


        return new JsonResponse($message, Response::HTTP_OK, ['Access-Control-Allow-Origin' => '*'], true);
    }

    /**
     * @Route("/openInstallation/{id}", name="openInstallation", methods={"GET"})
     */
    public function openInstallation(\App\Entity\Installation $installation, Installation $installationHandler){

        try{
            if(!$installation){
                throw new \Exception("l'installation demandé est introuvable");
            }
            $installationHandler->init()->openInstallation($installation);
            $message = $this->encoder->encode(["success" => false, "message" => "installation chargé"], 'json');

        }catch (\Exception $e){
            $message = $this->encoder->encode(["success" => false, "message" => $e->getMessage()], 'json');

        }

        return new JsonResponse($message, Response::HTTP_OK, ['Access-Control-Allow-Origin' => '*'], true);
    }

    /**
     * @param \App\Entity\Installation $installation
     * @Route("/updateInstallation/{id}", name="updateInstallation")
     * @return JsonResponse
     */
    public function updateInstallation(\App\Entity\Installation $installation, Installation $installationHandler, Request $request){
        set_time_limit(0);
        try{
            if(!$installation){
                throw new \Exception("l'installation demandé est introuvable");
            }
            $installationHandler->init($installation)->updateInstallation($request);
            $message = $this->encoder->encode(["success" => true, "message" => "installation mise à jour"], 'json');


        }catch (\Exception $e){
            $message = $this->encoder->encode(["success" => false, "message" => $e->getMessage()], 'json');

        }
        return new JsonResponse($message, Response::HTTP_OK, ['Access-Control-Allow-Origin' => '*'], true);
    }

    /**
     * @param \App\Entity\Installation $installation
     * @param Installation $installationHandler
     * @Route("/removeInstallation/{id}", name="removeInstallation")
     * @return JsonResponse
     */
    public function removeInstallation(\App\Entity\Installation $installation, Installation $installationHandler){
        set_time_limit(0);
        try{
            if(!$installation){
                throw new \Exception("l'installation demandé est introuvable");
            }
            $installationHandler->init($installation)->removeInstallation();
            $message = $this->encoder->encode(["success" => true, "message" => "installation supprimé"], 'json');

        }catch (\Exception $e){
            $message = $this->encoder->encode(["success" => false, "message" => $e->getMessage()], 'json');
        }
        return new JsonResponse($message, Response::HTTP_OK, ['Access-Control-Allow-Origin' => '*'], true);

    }

}

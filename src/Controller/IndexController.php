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
     * @Route("/addInstallation", name="addInstallation", methods={"POST"})
     */
    public function addInstallation(Installation $installation, Request $request){

        try{
            $installation->init()->addInstallation($request);
            $message = $this->encoder->encode(["success" => true, "message" => "Installation réussite"], 'json');
        }catch (\Exception $e){
            $message = $this->encoder->encode(["success" => false, "message" => "Installation déja existante"], 'json');
        }


        return new JsonResponse($message, Response::HTTP_OK, ['Access-Control-Allow-Origin' => '*'], true);
    }

    /**
     * @Route("/openInstallation/{id}", name="openInstallation")
     */
    public function openInstallation(\App\Entity\Installation $installation, Installation $installationHandler){

        try{
            if(!$installation){
                throw new \Exception("l'installation demandé est introuvable");
            }
            $installationHandler->init()->openInstallation($installation);
            return new JsonResponse(["success" => true, "message" => "installation chargé"], Response::HTTP_OK);

        }catch (\Exception $e){
            return new JsonResponse(["success" => false, "message" => $e->getMessage()], 500);
        }
    }

    /**
     * @param \App\Entity\Installation $installation
     * @Route("/updateInstallation/{id}", name="updateInstallation")
     * @return JsonResponse
     */
    public function updateInstallation(\App\Entity\Installation $installation, Installation $installationHandler, Request $request){
        try{
            if(!$installation){
                throw new \Exception("l'installation demandé est introuvable");
            }
            $installationHandler->init($installation)->updateInstallation($request);
            return new JsonResponse(["success" => true, "message" => "installation mise à jour"], Response::HTTP_OK);

        }catch (\Exception $e){
            return new JsonResponse(["success" => false, "message" => $e->getMessage()], 500);
        }
    }


}

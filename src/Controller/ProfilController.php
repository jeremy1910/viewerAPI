<?php
/**
 * Created by PhpStorm.
 * User: jeje
 * Date: 17/10/19
 * Time: 23:39
 */

namespace App\Controller;


use App\Entity\Installation;
use App\Entity\Profil;
use App\Repository\ProfilRepository;
use App\Services\FirstMaxResult;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerBuilder;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\Serializer\Encoder\DecoderInterface;
use Symfony\Component\Serializer\Encoder\EncoderInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;


class ProfilController extends AbstractController
{
    public function __construct(FirstMaxResult $firstMaxResult, ProfilRepository $profilRepository,  NormalizerInterface $normalizer, EncoderInterface $encoder, DecoderInterface $decoder)
    {

        $this->firstMaxResult = $firstMaxResult;
        $this->profilRepository = $profilRepository;
        $this->normalizer = $normalizer;
        $this->encoder = $encoder;
        $this->decoder = $decoder;
    }

    private $normalizer;
    private $encoder;
    private $decoder;
    private $firstMaxResult;
    private $profilRepository;

    /**
     * Retour l'esemble des profils
     * Le nombre de résultat est limité par les paramettres 'limit' et 'start'
     * Si ils ne sont pas precisés leur valeur par défaut sont : limit = 25, start = 0
     *
     * exemple : /api/{installation}/profils?start=25&limit=100
     * @Route("/api/{installation}/profils", name="profils", methods={"GET"})
     */
    public function profils(Installation $installation, Request $request)
    {

        $start = $request->query->get('start');
        $limit = $request->query->get('limit');

        $this->firstMaxResult->setFirstMaxresult($start, $limit);

        try{
            $result = $this->profilRepository->findByInstallation($installation, $limit, $start);
            $normalizedData['result'] = $this->normalizer->normalize($result['result'], null, ['groups' => ['profil']]);
            $normalizedData['maxCount'] = $result['countMax'];

            $this->decodePH($normalizedData);
            $normalizedData['success'] = true;
            $response = $this->encoder->encode($normalizedData, 'json');
        }
        catch (\Exception $e)
        {
            $normalizedData['result'] = $e->getMessage();
            $normalizedData['success'] = false;
            $response = $this->encoder->encode($normalizedData, 'json');
        }
        return new JsonResponse($response, Response::HTTP_OK, ['Access-Control-Allow-Origin' => '*'], true);

    }


    /**
     * Retour un profils en fonction de sont ID (ID en base de donnée)
     *
     * exemple /api/profils/2
     *
     * @Route("/api/profils/{id}", name="profils_get_one", methods={"GET"})
     */
    public function profils_get_one(Profil $profil){

        try{
            $normalizedData['result'][] = $this->normalizer->normalize($profil, null, ['groups' => ['profil']]);
            $normalizedData['maxCount'] = 1;
            $this->decodePH($normalizedData);

            $normalizedData['success'] = true;
            $response = $this->encoder->encode($normalizedData, 'json');
        }
        catch (\Exception $e)
        {
            $normalizedData['result'] = $e->getMessage();
            $normalizedData['success'] = false;
            $response = $this->encoder->encode($normalizedData, 'json');
        }
        return new JsonResponse($response, Response::HTTP_OK, ['Access-Control-Allow-Origin' => '*'], true);

    }

    /**
     * Retour un ensemble de profil en fonction du 'nom'
     *
     * les parametres HTTP sont :
     *
     * 'nom' =>  obligatoire, une partie du 'nom' à rechercher
     * 'limit" => optionnel, par defaut 25
     * 'start => optionnel, par defaut 0
     *
     * 'limit" et 'start' ne peuvent pas être déclaré individuellement l'un sans l'autre.
     *
     *  exemple /api/{installation}/profils/search/nom?nom=total&start=1&limit=100
     *
     * @Route("/api/{installation}/profils/search/nom", name="profils_search_nom", methods={"GET"})
     */
    public function profils_search_nom(Installation $installation, Request $request){

        $start = $request->query->get('start');
        $limit = $request->query->get('limit');
        $nom = $request->query->get('nom');

        $this->firstMaxResult->setFirstMaxresult($start, $limit);

        try{
            $result = $this->profilRepository->findByNom($nom, $installation,(int) $limit, (int) $start);
            $normalizedData['result'] = $this->normalizer->normalize($result['result'], null, ['groups' => ['profil']]);
            $normalizedData['maxCount'] = $result['countMax'];
            $this->decodePH($normalizedData);
            $normalizedData['success'] = true;
            $response = $this->encoder->encode($normalizedData, 'json');
        }
        catch (\Exception $e)
        {
            $normalizedData['result'] = $e->getMessage();
            $normalizedData['success'] = false;
            $response = $this->encoder->encode($normalizedData, 'json');
        }
        return new JsonResponse($response, Response::HTTP_OK, ['Access-Control-Allow-Origin' => '*'], true);
    }

    private function decodePH(array & $normalizedData){
        foreach ($normalizedData['result'] as $key => $result){
            foreach ($result["profilGlecteurVariable"] as $key2 =>$result2)
            {

                $normalizedData['result'][$key]["profilGlecteurVariable"][$key2]['variable']["extension"] = $this->decoder->decode( $result2["variable"]["extension"], 'json');

            }
        }
    }
}
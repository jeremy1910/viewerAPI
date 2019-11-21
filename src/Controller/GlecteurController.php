<?php

namespace App\Controller;

use App\Entity\Glecteur;
use App\Entity\Installation;
use App\Repository\GlecteurRepository;
use App\Services\FirstMaxResult;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\Serializer\Encoder\EncoderInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;


class GlecteurController extends AbstractController
{

    public function __construct(FirstMaxResult $firstMaxResult, GlecteurRepository $glecteurRepository, NormalizerInterface $normalizer, EncoderInterface $encoder)
    {

        $this->normalizer = $normalizer;
        $this->encoder = $encoder;
        $this->firstMaxResult = $firstMaxResult;
        $this->glecteurRepository = $glecteurRepository;
    }

    private $normalizer;
    private $encoder;
    private $firstMaxResult;
    private $glecteurRepository;

    /**
     * Retour l'esemble des glecteurs
     * Le nombre de résultat est limité par les paramettres 'limit' et 'start'
     * Si ils ne sont pas precisés leur valeur par défaut sont : limit = 25, start = 0
     *
     * exemple : /api/{installation}/glecteurs?start=25&limit=100
     * @Route("/api/{installation}/glecteurs", name="glecteurs", methods={"GET"})
     */
    public function glecteurs(Installation $installation, Request $request)
    {

        $start = $request->query->get('start');
        $limit = $request->query->get('limit');

        $this->firstMaxResult->setFirstMaxresult($start, $limit);

        try{
            $result = $this->glecteurRepository->findByInstallation($installation, $limit, $start);
            $normalizedData['result'] = $this->normalizer->normalize($result['result'], null, ['groups' => ['glecteur']]);
            $normalizedData['maxCount'] = $result['countMax'];
            $json = $this->encoder->encode($normalizedData, 'json');
            return new JsonResponse($json, Response::HTTP_OK, ['Access-Control-Allow-Origin' => '*'], true);
        }
        catch (\Exception $e)
        {
            $error = $e->getMessage();
            return new JsonResponse($error, Response::HTTP_BAD_REQUEST, ['Access-Control-Allow-Origin' => '*'], false);
        }

    }


    /**
     * Retour un glecteurs en fonction de sont ID (ID en base de donnée)
     *
     * exemple /api/glecteurs/2
     *
     * @Route("/api/glecteurs/{id}", name="glecteurs_get_one", methods={"GET"})
     */
    public function glecteurs_get_one(Glecteur $glecteur){

        try{
            $normalizedData['result'][] = $this->normalizer->normalize($glecteur, null, ['groups' => ['glecteur']]);
            $normalizedData['maxCount'] = 1;

            $json = $this->encoder->encode($normalizedData, 'json');
            return new JsonResponse($json, Response::HTTP_OK, ['Access-Control-Allow-Origin' => '*'], true);
        }catch (\Exception $e){
            $error = $e->getMessage();
            return new JsonResponse($error, Response::HTTP_BAD_REQUEST, ['Access-Control-Allow-Origin' => '*'], false);
        }

    }

    /**
     * Retour un ensemble de glecteur en fonction du 'nom'
     *
     * les parametres HTTP sont :
     *
     * 'nom' =>  obligatoire, une partie du 'nom' à rechercher
     * 'limit" => optionnel, par defaut 25
     * 'start => optionnel, par defaut 0
     *
     * 'limit" et 'start' ne peuvent pas être déclaré individuellement l'un sans l'autre.
     *
     *  exemple /api/{installation}/glecteurs/search/nom?nom=JEREMY&start=1&limit=100
     *
     * @Route("/api/{installation}/glecteurs/search/nom", name="glecteurs_search_nom", methods={"GET"})
     */
    public function glecteurs_search_nom(Installation $installation, Request $request){

        $start = $request->query->get('start');
        $limit = $request->query->get('limit');
        $nom = $request->query->get('nom');

        $this->firstMaxResult->setFirstMaxresult($start, $limit);

        try{
            $result = $this->glecteurRepository->findByNom($nom, $installation,(int) $limit, (int) $start);
            $normalizedData['result'] = $this->normalizer->normalize($result['result'], null, ['groups' => ['glecteur']]);
            $normalizedData['maxCount'] = $result['countMax'];
            $json = $this->encoder->encode($normalizedData, 'json');
            return new JsonResponse($json, Response::HTTP_OK, ['Access-Control-Allow-Origin' => '*'], true);
        }catch (\Exception $e){
            $error = $e->getMessage();
            return new JsonResponse($error, Response::HTTP_BAD_REQUEST, ['Access-Control-Allow-Origin' => '*'], false);
        }
    }

}

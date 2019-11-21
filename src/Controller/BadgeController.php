<?php
/**
 * Created by PhpStorm.
 * User: jeje
 * Date: 17/10/19
 * Time: 23:48
 */

namespace App\Controller;


use App\Entity\Badge;
use App\Entity\Installation;
use App\Repository\BadgeRepository;
use App\Services\FirstMaxResult;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\EncoderInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;


class BadgeController extends AbstractController
{
    public function __construct(BadgeRepository $badgeRepository, FirstMaxResult $firstMaxResult,   NormalizerInterface $normalizer, EncoderInterface $encoder)
    {

        $this->badgeRepository = $badgeRepository;
        $this->firstMaxResult = $firstMaxResult;
        $this->normalizer = $normalizer;
        $this->encoder = $encoder;
    }

    private $normalizer;
    private $encoder;
    private $badgeRepository;
    private $firstMaxResult;


    /**
     * Retour l'esemble des badges
     * Le nombre de résultat est limité par les paramettres 'limit' et 'start'
     * Si ils ne sont pas precisés leur valeur par défaut sont : limit = 25, start = 0
     *
     * exemple : /api/badges?start=25&limit=100
     *
     * @Route("/api/{installation}/badges", name="badges", methods={"GET"})
     */
    public function badges(Installation $installation, Request $request)
    {
        $start = $request->query->get('start');
        $limit = $request->query->get('limit');

        $this->firstMaxResult->setFirstMaxresult($start, $limit);

        try{
            $result = $this->badgeRepository->findByInstallation($installation, $limit, $start);
            $normalizedData['result'] = $this->normalizer->normalize($result['result'], null, ['groups' => ['badge']]);
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
     * Retour un badge en fonction de sont ID (ID en base de donnée)
     *
     * exemple /api/badges/4000
     *
     * @Route("/api/badges/{id}", name="badges_get_one", methods={"GET"})
     */
    public function badges_get_one(Badge $badge){
        try {
            $normalizedData['result'][] = $this->normalizer->normalize($badge, null, ['groups' => ['badge']]);
            $normalizedData['maxCount'] = 1;

            $json = $this->encoder->encode($normalizedData, 'json');
            return new JsonResponse($json, Response::HTTP_OK, ['Access-Control-Allow-Origin' => '*'], true);
        }catch (\Exception $e){
            $error = $e->getMessage();
            return new JsonResponse($error, Response::HTTP_BAD_REQUEST, ['Access-Control-Allow-Origin' => '*'], false);
        }

    }


    /**
     * retour un ensemble de badge en fonction du 'nom'
     *
     * les parametre HTTP sont :
     *
     * 'nom' =>  obligatoire, une partie du 'nom' à rechercher
     * 'limit" => optionnel, par defaut 25
     * 'start => optionnel, par defaut 0
     *
     * 'limit" et 'start' ne peuvent pas être déclaré individuellement l'un sans l'autre.
     *
     *  exemple /api/{installation}/badges/search/nom?nom=JEREMY&start=1&limit=100
     *
     * @Route("/api/{installation}/badges/search/nom", name="badges_search_nom", methods={"GET"})
     */
    public function badges_search_nom(Installation $installation, Request $request){


        $start = $request->query->get('start');
        $limit = $request->query->get('limit');
        $nom = $request->query->get('nom');

        $this->firstMaxResult->setFirstMaxresult($start, $limit);

        try {
            $result = $this->badgeRepository->findByNom($nom, $installation,(int) $limit, (int) $start);
            $normalizedData['result'] = $this->normalizer->normalize($result['result'], null, ['groups' => ['badge']]);
            $normalizedData['maxCount'] = $result['countMax'];
            $json = $this->encoder->encode($normalizedData, 'json');
            return new JsonResponse($json, Response::HTTP_OK, ['Access-Control-Allow-Origin' => '*'], true);
        }catch (\Exception $e){
            $error = $e->getMessage();
            return new JsonResponse($error, Response::HTTP_BAD_REQUEST, ['Access-Control-Allow-Origin' => '*'], false);
        }
    }


    /**
     *
     *  * retour un ensemble de badge en fonction du 'code1"
     *
     * les parametre HTTP sont :
     *
     * 'code1' => string, obligatoire, une partie du 'code1' à rechercher
     * 'limit" => optionnel, par defaut 25
     * 'start => optionnel, par defaut 0
     *
     * 'limit" et 'start' ne peuvent pas être déclaré individuellement l'un sans l'autre.
     *
     * exemple /api/{installation}/badges/search/code1?code1=000000156478&start=1&limit=100
     *
     * @Route("/api/{installation}/badges/search/code1", name="badges_search_code1", methods={"GET"})
     */
    public function badges_search_code1(Installation $installation, Request $request){


        $start = $request->query->get('start');
        $limit = $request->query->get('limit');
        $code1 = $request->query->get('code1');

        $this->firstMaxResult->setFirstMaxresult($start, $limit);

        try {
            $result = $this->badgeRepository->findByCode1($code1, $installation,(int) $limit, (int) $start);
            $normalizedData['result'] = $this->normalizer->normalize($result['result'], null, ['groups' => ['badge']]);
            $normalizedData['maxCount'] = $result['countMax'];
            $json = $this->encoder->encode($normalizedData, 'json');
            return new JsonResponse($json, Response::HTTP_OK, ['Access-Control-Allow-Origin' => '*'], true);
        }catch (\Exception $e){
            $error = $e->getMessage();
            return new JsonResponse($error, Response::HTTP_BAD_REQUEST, ['Access-Control-Allow-Origin' => '*'], false);
        }
    }

    /**
     *
     * retour un ensemble de badge en fonction du 'prenom"
     *
     * les parametre HTTP sont :
     *
     * 'prenom' => obligatoire, une partie du 'prenom' à rechercher
     * 'limit" => optionnel, par defaut 25
     * 'start => optionnel, par defaut 0
     *
     * 'limit" et 'start' ne peuvent pas être déclaré individuellement l'un sans l'autre.
     *
     * exemple /api/{installation}/badges/search/prenom?prenom=JEREMY&start=1&limit=100
     *
     * @Route("/api/{installation}/badges/search/prenom", name="badges_search_prenom", methods={"GET"})
     */
    public function badges_search_prenom(Installation $installation, Request $request){

        $prenom = $request->query->get('prenom');
        $start = $request->query->get('start');
        $limit = $request->query->get('limit');

        $this->firstMaxResult->setFirstMaxresult($start, $limit);
        try {
            $result = $this->badgeRepository->findByCode1($prenom, $installation,(int) $limit, (int) $start);
            $normalizedData['result'] = $this->normalizer->normalize($result['result'], null, ['groups' => ['badge']]);
            $normalizedData['maxCount'] = $result['countMax'];
            $json = $this->encoder->encode($normalizedData, 'json');
            return new JsonResponse($json, Response::HTTP_OK, ['Access-Control-Allow-Origin' => '*'], true);
        }catch (\Exception $e){
            $error = $e->getMessage();
            return new JsonResponse($error, Response::HTTP_BAD_REQUEST, ['Access-Control-Allow-Origin' => '*'], false);
        }
    }
}


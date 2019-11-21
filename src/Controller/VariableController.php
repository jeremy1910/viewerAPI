<?php

namespace App\Controller;

use App\Entity\Installation;
use App\Entity\Variable;
use App\Repository\VariableRepository;
use App\Services\FirstMaxResult;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\EncoderInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


class VariableController extends AbstractController
{
    public function __construct(VariableRepository $variableRepository, FirstMaxResult $firstMaxResult, NormalizerInterface $normalizer, EncoderInterface $encoder)
    {
        $this->variableRepository = $variableRepository;
        $this->firstMaxResult = $firstMaxResult;
        $this->normalizer = $normalizer;
        $this->encoder = $encoder;
    }

    private $normalizer;
    private $encoder;
    private $variableRepository;
    private $firstMaxResult;


    /**
     * Retour l'esemble des variables
     * Le nombre de résultat est limité par les paramettres 'limit' et 'start'
     * Si ils ne sont pas precisés leur valeur par défaut sont : limit = 25, start = 0
     *
     * exemple : /api/{installation}/variables?start=25&limit=100
     *
     * @Route("/api/{installation}/variables", name="variables", methods={"GET"})
     */
    public function variables(Installation $installation, Request $request)
    {
        $start = $request->query->get('start');
        $limit = $request->query->get('limit');

        $this->firstMaxResult->setFirstMaxresult($start, $limit);

        try {
            $result = $this->variableRepository->findByInstallation($installation, $limit, $start);
            $normalizedData['result'] = $this->normalizer->normalize($result['result'], null, ['groups' => ['variable']]);
            $normalizedData['maxCount'] = $result['countMax'];
            $json = $this->encoder->encode($normalizedData, 'json');
            return new JsonResponse($json, Response::HTTP_OK, ['Access-Control-Allow-Origin' => '*'], true);
        } catch (\Exception $e) {
            $error = $e->getMessage();
            return new JsonResponse($error, Response::HTTP_BAD_REQUEST, ['Access-Control-Allow-Origin' => '*'], false);
        }

    }

    /**
     * Retour un badge en fonction de sont ID (ID en base de donnée)
     *
     * exemple /api/variables/4000
     *
     * @Route("/api/variables/{id}", name="variables_get_one", methods={"GET"})
     */
    public function variables_get_one(Variable $variable)
    {
        try {
            $normalizedData['result'][] = $this->normalizer->normalize($variable, null, ['groups' => ['variable']]);
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
     *  exemple /api/{installation}/variables/search/nom?nom=L.001&start=1&limit=100
     *
     * @Route("/api/{installation}/variables/search/nom", name="variables_search_nom", methods={"GET"})
     */
    public function variables_search_nom(Installation $installation, Request $request)
    {

        $start = $request->query->get('start');
        $limit = $request->query->get('limit');
        $nom = $request->query->get('nom');

        $this->firstMaxResult->setFirstMaxresult($start, $limit);

        try {
            $result = $this->variableRepository->findByNom($nom, $installation,(int) $limit, (int) $start);
            $normalizedData['result'] = $this->normalizer->normalize($result['result'], null, ['groups' => ['variable']]);
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
     *  * retour un ensemble de badge en fonction du 'description"
     *
     * les parametre HTTP sont :
     *
     * 'description' => string, obligatoire, une partie du 'description' à rechercher
     * 'limit" => optionnel, par defaut 25
     * 'start => optionnel, par defaut 0
     *
     * 'limit" et 'start' ne peuvent pas être déclaré individuellement l'un sans l'autre.
     *
     * exemple /api/{installation}/variables/search/description?description=lecteur&start=1&limit=100
     *
     * @Route("/api/{installation}/variables/search/description", name="variables_search_description", methods={"GET"})
     */
    public function variables_search_description(Installation $installation, Request $request)
    {

        $start = $request->query->get('start');
        $limit = $request->query->get('limit');
        $description = $request->query->get('description');

        $this->firstMaxResult->setFirstMaxresult($start, $limit);

        try {
            $result = $this->variableRepository->findByDescription($description, $installation,(int) $limit, (int) $start);
            $normalizedData['result'] = $this->normalizer->normalize($result['result'], null, ['groups' => ['variable']]);
            $normalizedData['maxCount'] = $result['countMax'];
            $json = $this->encoder->encode($normalizedData, 'json');
            return new JsonResponse($json, Response::HTTP_OK, ['Access-Control-Allow-Origin' => '*'], true);
        }catch (\Exception $e){
            $error = $e->getMessage();
            return new JsonResponse($error, Response::HTTP_BAD_REQUEST, ['Access-Control-Allow-Origin' => '*'], false);
        }
    }

    
}
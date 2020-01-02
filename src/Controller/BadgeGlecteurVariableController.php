<?php

namespace App\Controller;

use App\Entity\Installation;
use App\Repository\BadgeGlecteurVariableRepository;
use App\Repository\BadgeRepository;
use App\Repository\VariableRepository;
use App\Services\FirstMaxResult;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\EncoderInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Encoder\DecoderInterface;

class BadgeGlecteurVariableController extends AbstractController
{

	/**
	 * BadgeGlecteurVariableController constructor.
	 * @param BadgeRepository $badgeRepository
	 * @param FirstMaxResult $firstMaxResult
	 * @param NormalizerInterface $normalizer
	 * @param EncoderInterface $encoder
	 * @param DecoderInterface $decoder
	 *
	 */
	public function __construct(BadgeRepository $badgeRepository, FirstMaxResult $firstMaxResult, NormalizerInterface $normalizer, EncoderInterface $encoder,  DecoderInterface $decoder)
	{
		$this->badgeRepository = $badgeRepository;
		$this->firstMaxResult = $firstMaxResult;
		$this->normalizer = $normalizer;
		$this->encoder = $encoder;
		$this->decoder = $decoder;
	}
	private $normalizer;
	private $encoder;
	private $badgeRepository;
	private $firstMaxResult;
	private $decoder;

	/**
	 * @param Installation $installation
	 * @param Request $request
	 * @return JsonResponse
	 * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
	 * @Route("/api/{installation}/badgeGlecteurVariable", name="badges_search_individual_right", methods={"GET"})
	 */
	public function badges_search_individual_right(Installation $installation, Request $request){

		$start = $request->query->get('start');
		$limit = $request->query->get('limit');

		$this->firstMaxResult->setFirstMaxresult($start, $limit);
		try {
			$result = $this->badgeRepository->findByInstallation($installation,(int) $limit, (int) $start, true);
			$normalizedData['result'] = $this->normalizer->normalize($result['result'], null, ['groups' => ['badge']]);
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
	 * @param Installation $installation
	 * @param Request $request
	 * @return JsonResponse
	 * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
	 * @Route("/api/{installation}/badgeGlecteurVariable/search/nom", name="badges_search_individual_right_nom", methods={"GET"})
	 */
	public function badges_search_individual_right_nom(Installation $installation, Request $request){

		$start = $request->query->get('start');
		$limit = $request->query->get('limit');
		$nom = $request->query->get('nom');
		$this->firstMaxResult->setFirstMaxresult($start, $limit);

		try {
			$result = $this->badgeRepository->findByNom($nom, $installation,(int) $limit, (int) $start, true);
			$normalizedData['result'] = $this->normalizer->normalize($result['result'], null, ['groups' => ['badge']]);
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
	 * @param Installation $installation
	 * @param Request $request
	 * @return JsonResponse
	 * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
	 * @Route("/api/{installation}/badgeGlecteurVariable/search/code1", name="badges_search_individual_right_code1", methods={"GET"})
	 */
	public function badges_search_individual_right_code1(Installation $installation, Request $request){


		$start = $request->query->get('start');
		$limit = $request->query->get('limit');
		$code1 = $request->query->get('code1');

		$this->firstMaxResult->setFirstMaxresult($start, $limit);

		try {
			$result = $this->badgeRepository->findByCode1($code1, $installation,(int) $limit, (int) $start, true);
			$normalizedData['result'] = $this->normalizer->normalize($result['result'], null, ['groups' => ['badge']]);
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
	 * @param Installation $installation
	 * @param Request $request
	 * @return JsonResponse
	 * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
	 * @Route("/api/{installation}/badgeGlecteurVariable/search/prenom", name="badges_search_individual_right_prenom", methods={"GET"})
	 */
	public function badges_search_individual_right_prenom(Installation $installation, Request $request){

		$prenom = $request->query->get('prenom');
		$start = $request->query->get('start');
		$limit = $request->query->get('limit');

		$this->firstMaxResult->setFirstMaxresult($start, $limit);
		try {
			$result = $this->badgeRepository->findByPrenom($prenom, $installation,(int) $limit, (int) $start, true);
			$normalizedData['result'] = $this->normalizer->normalize($result['result'], null, ['groups' => ['badge']]);
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
	 * @param array $normalizedData
	 */
	private function decodePH(array & $normalizedData)
	{
		foreach ($normalizedData['result'] as $key => $result){
			foreach ($result["badgeGlecteurVariable"] as $key2 =>$result2)
			{
				$normalizedData['result'][$key]["badgeGlecteurVariable"][$key2]['variable']["extension"] = $this->decoder->decode( $result2["variable"]["extension"], 'json');
			}
		}
	}
}

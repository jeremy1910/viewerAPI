<?php
/**
 * Created by PhpStorm.
 * User: jeje
 * Date: 16/12/19
 * Time: 22:04
 */

namespace App\Controller;


use App\Entity\Installation;
use App\Repository\BadgeGlecteurVariableRepository;
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
	 * @param BadgeGlecteurVariableRepository $badgeGlecteurVariableRepository
	 * @param FirstMaxResult $firstMaxResult
	 * @param NormalizerInterface $normalizer
	 * @param EncoderInterface $encoder
	 * @param DecoderInterface $decoder
	 *
	 */
	public function __construct(BadgeGlecteurVariableRepository $badgeGlecteurVariableRepository, FirstMaxResult $firstMaxResult, NormalizerInterface $normalizer, EncoderInterface $encoder,  DecoderInterface $decoder)
	{
		$this->badgeGlecteurVariableRepository = $badgeGlecteurVariableRepository;
		$this->firstMaxResult = $firstMaxResult;
		$this->normalizer = $normalizer;
		$this->encoder = $encoder;
		$this->decoder = $decoder;
	}
	private $normalizer;
	private $encoder;
	private $badgeGlecteurVariableRepository;
	private $firstMaxResult;
	private $decoder;

	/**
	 * @param Installation $installation
	 * @param Request $request
	 * @return JsonResponse
	 * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
	 *
	 */
	public function badgeGlecteurVariableRepository(Installation $installation, Request $request)
	{
		$start = $request->query->get('start');
		$limit = $request->query->get('limit');

		$this->firstMaxResult->setFirstMaxresult($start, $limit);
		try {
			$result = $this->badgeGlecteurVariableRepository->findByInstallation($installation, $limit, $start);
			$normalizedData['result'] = $this->normalizer->normalize($result['result'], null, ['groups' => ['badgeGlecteurVariable']]);
			$normalizedData['maxCount'] = $result['countMax'];
			$this->decodePH($normalizedData);
			$normalizedData['success'] = true;
			$response = $this->encoder->encode($normalizedData, 'json');

		} catch (\Exception $e) {
			$normalizedData['result'] = $e->getMessage();
			$normalizedData['success'] = false;
			$response = $this->encoder->encode($normalizedData, 'json');
		}
		return new JsonResponse($response, Response::HTTP_OK, ['Access-Control-Allow-Origin' => '*'], true);
	}

	private function decodePH(array & $normalizedData){
		foreach ($normalizedData['result'] as $key => $badgeGlecteurVariableRepository){
			if($badgeGlecteurVariableRepository["variable"]["extension"]){
				$normalizedData['result'][$key]["variable"]["extension"] = $this->decoder->decode( $badgeGlecteurVariableRepository["variable"]["extension"], 'json');
			}
		}
	}
}
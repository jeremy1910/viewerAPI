<?php

namespace App\Services;

use App\Entity\BadgeGlecteurVariable;
use App\Entity\ProfilGlecteurVariable;
use App\Repository\BadgeRepository;
use App\Repository\GlecteurRepository;
use App\Repository\ProfilRepository;
use App\Repository\VariableRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class BadgeGlecteurVariableService extends ParserService
{
	public function __construct(ParameterBagInterface $parameterBag, EntityManagerInterface $entityManager, BadgeRepository $badgeRepository, GlecteurRepository $glecteurRepository, VariableRepository $variableRepository)
	{
		parent::__construct($parameterBag, $entityManager);

		$this->badgeRepository = $badgeRepository;
		$this->glecteurRepository = $glecteurRepository;
		$this->variableRepository = $variableRepository;
	}

	private $badgeRepository;
	private $glecteurRepository;
	private $variableRepository;

	public function makeAssociation(){

		$records = $this->stmt->process($this->reader);

		foreach ($records as $offset => $record) {
			$badgeGlecteurVariable = new BadgeGlecteurVariable();

			$badgeGlecteurVariable->setBadge(key_exists($record['Badge'], self::$badges) ? self::$badges[$record['Badge']] : null );
			$badgeGlecteurVariable->setVariable(key_exists($record['PHoraire'], self::$variables) ? self::$variables[$record['PHoraire']] : null );
			$badgeGlecteurVariable->setGlecteur(key_exists($record['GLecteur'], self::$glecteurs) ? self::$glecteurs[$record['GLecteur']] : null );
			$badgeGlecteurVariable->setInstallation($this->installation);
			if($badgeGlecteurVariable->getGlecteur() && $badgeGlecteurVariable->getVariable() && $badgeGlecteurVariable->getBadge()){
				$this->entityManager->persist($badgeGlecteurVariable);
			}
		}
		$this->entityManager->flush();



	}
}
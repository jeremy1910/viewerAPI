<?php

namespace App\Services;

use App\Entity\Badge;
use App\Entity\BadgeGlecteurVariable;
use App\Entity\Glecteur;
use App\Entity\ProfilGlecteurVariable;
use App\Entity\Variable;
use App\Repository\BadgeRepository;
use App\Repository\GlecteurRepository;
use App\Repository\ProfilRepository;
use App\Repository\VariableRepository;
use Doctrine\ORM\EntityManagerInterface;
use DoctrineBatchUtils\BatchProcessing\SimpleBatchIteratorAggregate;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class BadgeGlecteurVariableService extends ParserService
{
	public function __construct(ParameterBagInterface $parameterBag, EntityManagerInterface $entityManager, BadgeRepository $badgeRepository, GlecteurRepository $glecteurRepository, VariableRepository $variableRepository, LoggerInterface $logger)
	{
		parent::__construct($parameterBag, $entityManager, $logger);
		$this->badgeRepository = $badgeRepository;
		$this->glecteurRepository = $glecteurRepository;
		$this->variableRepository = $variableRepository;
	}

	private $badgeRepository;
	private $glecteurRepository;
	private $variableRepository;

	/**
	 * Droits Individuels
	 */

	public function makeAssociation(){

		$records = $this->stmt->process($this->reader);

		$batchSize = 100;
		$i = 0;
		$this->entityManager->clear();
		foreach ($records as $key => $record){
			$badgeGlecteurVariable = new BadgeGlecteurVariable();

			$badge = $this->entityManager->getRepository(Badge::class)->findOneBy(['appID' => $record['Badge'], 'installation' => $this->installation->getId()]);
			if($badge === null){
				$badge = key_exists($record['Badge'], self::$badges) ? self::$badges[$record['Badge']] : null;

			}


			$variable = $this->entityManager->getRepository(Variable::class)->findOneBy(['appID' => $record['PHoraire'], 'installation' => $this->installation->getId()]);
			if($variable === null){
				$variable = key_exists($record['PHoraire'], self::$variables) ? self::$variables[$record['PHoraire']] : null;

			}

			$glecteur = $this->entityManager->getRepository(Glecteur::class)->findOneBy(['appID' => $record['GLecteur'], 'installation' => $this->installation->getId()]);
			if($glecteur === null){
				$glecteur = key_exists($record['GLecteur'], self::$glecteurs) ? self::$glecteurs[$record['GLecteur']] : null;

			}


			if($badge && $glecteur && $variable){
				$installation = $this->entityManager->find(\App\Entity\Installation::class, $this->installation->getId());
				$variable->setInstallation($installation);
				$badge->setInstallation($installation);
				$glecteur->setInstallation($installation);
				$badgeGlecteurVariable->setBadge($badge);
				$badgeGlecteurVariable->setVariable($variable);
				$badgeGlecteurVariable->setGlecteur($glecteur);
				$this->entityManager->persist($badge);
				$this->entityManager->persist($variable);
				$this->entityManager->persist($glecteur);
				$badgeGlecteurVariable->setInstallation($installation);
				$this->entityManager->persist($badgeGlecteurVariable);
				unset(self::$badges2[$record['Badge']]);
				unset(self::$variables2[$record['PHoraire']]);
				unset(self::$glecteurs2[$record['GLecteur']]);
			}
			if (($i % $batchSize) === 0) {
				$this->entityManager->flush();
				$this->entityManager->clear(BadgeGlecteurVariable::class); // Detaches all objects from Doctrine!

			}
			$i++;
		}

		$this->entityManager->flush(); //Persist objects that did not make up an entire batch
		$this->entityManager->clear(BadgeGlecteurVariable::class);

		$this->logger->info("**** Création de la table associative Badge - Glecteur - Variable (PH) ****");


	}
}
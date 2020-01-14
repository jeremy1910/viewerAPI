<?php

namespace App\Services;


use App\Entity\Badge;
use App\Entity\Glecteur;
use App\Entity\Profil;
use App\Entity\Variable;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class PersistAllElementsService extends ParserService
{
	public function __construct(ParameterBagInterface $parameterBag, EntityManagerInterface $entityManager, LoggerInterface $logger, ValidatorInterface $validator)
	{
		parent::__construct($parameterBag, $entityManager, $logger);
		$this->validator = $validator;
	}

	private $tmpinstallation;
	private $validator;
	/**
	 * @param \App\Entity\Installation $installation
	 * @throws \Exception
	 */
	public function shouldPersistAllElement(\App\Entity\Installation $installation){
		$this->entityManager->clear();
		$mode = $installation->getMode();

		$this->tmpinstallation = $this->entityManager->find(\App\Entity\Installation::class, $installation->getId());
		$this->logger->info("le mode est : $mode");
		if($mode >= 0 && $mode <=15){
			if($mode > 0)
			{
				$binMode = decbin($mode);

				$this->logger->info($binMode);
				if(isset($binMode[0])){
					if($binMode[0] === '1')
					{
						$this->logger->info("*** Persistance de toutes les variables ". count(self::$variables2) ." ***");
						$this->persistVariables();
					}
				}
				if(isset($binMode[1])){
					if($binMode[1] === '1'){
						$this->persistGlecteur();
						$this->logger->info("*** Persistance de touts les groupe de lecteur *** ". count(self::$glecteurs2) ." ***");
					}
				}
				if(isset($binMode[2])) {
					if ($binMode[2] === '1') {
						$this->persistProfil();
						$this->logger->info("*** Persistance de touts les profils *** " . count(self::$profils2) . " ***");
					}
				}
				if(isset($binMode[3])) {
					if ($binMode[3] === '1') {
						$this->persistBadges();
						$this->logger->info("*** Persistance de touts les badges *** " . count(self::$badges2) . " ***");
					}
				}
			}


		}else{
			throw new \Exception("erreur de sur le mode d'import selectionné : '$mode' n'est pas valide");
		}
	}

	private function persistGlecteur(){
		$i=0;
		foreach (self::$glecteurs2 as $glecteur){
			/**
			 * @var $glecteur Glecteur
			 */

					$glecteur->setInstallation($this->tmpinstallation);
					$this->entityManager->persist($glecteur);


			if (($i % 100) === 0) {
				$this->entityManager->flush();
				$this->entityManager->clear(Glecteur::class);
			}
			$i++;
		}
		$this->entityManager->flush();
		$this->entityManager->clear(Glecteur::class);
	}

	private function persistProfil(){
		$i=0;
		foreach (self::$profils2 as $profil){
			/**
			 * @var $profil Profil
			 */


				$profil->setInstallation($this->tmpinstallation);
				$this->entityManager->persist($profil);


			if (($i % 100) === 0) {
				$this->entityManager->flush();
				$this->entityManager->clear(Profil::class);
			}
			$i++;
		}
		$this->entityManager->flush();
		$this->entityManager->clear(Profil::class);
	}

	private function persistVariables(){
		$i=0;
		foreach (self::$variables2 as $variable){
			/**
			 * @var $variable Variable
			 */


				$variable->setInstallation($this->tmpinstallation);
				$this->entityManager->persist($variable);


			if (($i % 100) === 0) {
				$this->entityManager->flush();
				$this->entityManager->clear(Variable::class);
			}
			$i++;
		}
		$this->entityManager->flush();
		$this->entityManager->clear(Variable::class);
	}

	private function persistBadges(){
		$i=0;
		foreach (self::$badges2 as $badge){
			/**
			 * @var $badge Badge
			 */


				$badge->setInstallation($this->tmpinstallation);
				$this->entityManager->persist($badge);


			if (($i % 100) === 0) {
				$this->entityManager->flush();
				$this->entityManager->clear(Badge::class);
			}
			$i++;
		}
		$this->entityManager->flush();
		$this->entityManager->clear(Badge::class);
	}

}
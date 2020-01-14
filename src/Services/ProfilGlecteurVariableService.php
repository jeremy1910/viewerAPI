<?php
/**
 * Created by PhpStorm.
 * User: jeje
 * Date: 06/10/19
 * Time: 10:25
 */

namespace App\Services;


use App\Entity\Glecteur;
use App\Entity\Profil;
use App\Entity\ProfilGlecteurVariable;
use App\Entity\Variable;
use App\Repository\GlecteurRepository;
use App\Repository\ProfilRepository;
use App\Repository\VariableRepository;
use Doctrine\ORM\EntityManagerInterface;
use DoctrineBatchUtils\BatchProcessing\SimpleBatchIteratorAggregate;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class ProfilGlecteurVariableService extends ParserService
{

	public function __construct(ParameterBagInterface $parameterBag, EntityManagerInterface $entityManager, ProfilRepository $profilRepository, GlecteurRepository $glecteurRepository, VariableRepository $variableRepository, LoggerInterface $logger)
	{
		parent::__construct($parameterBag, $entityManager, $logger);

		$this->profilRepository = $profilRepository;
		$this->glecteurRepository = $glecteurRepository;
		$this->variableRepository = $variableRepository;
	}

	private $profilRepository;
	private $glecteurRepository;
	private $variableRepository;


	/**
	 * Profils d'accès
	 */
	public function makeAssociation(){

		$records = $this->stmt->process($this->reader);

		$batchSize = 100;
		$i = 0;

		foreach ($records as $key => $record){
			$profilGlecteurVariable = new ProfilGlecteurVariable();



			$profil = $this->entityManager->getRepository(Profil::class)->findOneBy(['appID' => $record['Profil'], 'installation' => $this->installation->getId()]);
			if($profil === null){
				$profil = key_exists($record['Profil'], self::$profils) ? self::$profils[$record['Profil']] : null;

			}


			$variable = $this->entityManager->getRepository(Variable::class)->findOneBy(['appID' => $record['PHoraire'], 'installation' => $this->installation->getId()]);
			if($variable === null){
				$variable = key_exists($record['PHoraire'], self::$variables) ? self::$variables[$record['PHoraire']] : null;

			}

			$glecteur = $this->entityManager->getRepository(Glecteur::class)->findOneBy(['appID' => $record['GLecteur'], 'installation' => $this->installation->getId()]);
			if($glecteur === null){
				$glecteur = key_exists($record['GLecteur'], self::$glecteurs) ? self::$glecteurs[$record['GLecteur']] : null;

			}

			if($profil && $glecteur && $variable){
				$installation = $this->entityManager->find(\App\Entity\Installation::class, $this->installation->getId());
				$variable->setInstallation($installation);
				$profil->setInstallation($installation);
				$glecteur->setInstallation($installation);
				$profilGlecteurVariable->setProfil($profil);
				$profilGlecteurVariable->setVariable($variable);
				$profilGlecteurVariable->setGlecteur($glecteur );
				$this->entityManager->persist($profil);
				$this->entityManager->persist($variable);
				$this->entityManager->persist($glecteur);
				$profilGlecteurVariable->setInstallation($installation);
				$this->entityManager->persist($profilGlecteurVariable);
				unset(self::$profils2[$record['Profil']]);
				unset(self::$variables2[$record['PHoraire']]);
				unset(self::$glecteurs2[$record['GLecteur']]);
			}
			if (($i % $batchSize) === 0) {
				$this->entityManager->flush();
				$this->entityManager->clear(ProfilGlecteurVariable::class); // Detaches all objects from Doctrine!
			}
			$i++;
		}

		$this->entityManager->flush(); //Persist objects that did not make up an entire batch
		$this->entityManager->clear(ProfilGlecteurVariable::class);


		$this->logger->info("**** Création de la table associative Profil - Glecteur - Variable (PH) ****");


	}
}
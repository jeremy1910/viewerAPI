<?php
/**
 * Created by PhpStorm.
 * User: jeje
 * Date: 06/10/19
 * Time: 10:25
 */

namespace App\Services;


use App\Entity\ProfilGlecteurVariable;
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
//		$iterable = SimpleBatchIteratorAggregate::fromTraversableResult(
//			call_user_func(function () use ($records) {
//				foreach ($records as $offset => $record) {
//					$this->entityManager->merge($this->installation);
//					$profilGlecteurVariable = new ProfilGlecteurVariable();
//					$profilGlecteurVariable->setProfil(key_exists($record['Profil'], self::$profils) ? self::$profils[$record['Profil']] : null );
//					$profilGlecteurVariable->setVariable(key_exists($record['PHoraire'], self::$variables) ? self::$variables[$record['PHoraire']] : null );
//					$profilGlecteurVariable->setGlecteur(key_exists($record['GLecteur'], self::$glecteurs) ? self::$glecteurs[$record['GLecteur']] : null );
//					$profilGlecteurVariable->setInstallation($this->installation);
//					if($profilGlecteurVariable->getGlecteur() && $profilGlecteurVariable->getVariable() && $profilGlecteurVariable->getProfil()){
//						$this->entityManager->persist($profilGlecteurVariable);
//					}
//					yield $offset;
//				}
//			}),
//			$this->entityManager,
//			100 // flush/clear after 100 iterations
//		);
//		foreach ($iterable as $record) {}

		$batchSize = 100;
		$i = 0;
		foreach ($records as $key => $record){
			$profilGlecteurVariable = new ProfilGlecteurVariable();
			$profilGlecteurVariable->setProfil(key_exists($record['Profil'], self::$profils) ? self::$profils[$record['Profil']] : null );
			$profilGlecteurVariable->setVariable(key_exists($record['PHoraire'], self::$variables) ? self::$variables[$record['PHoraire']] : null );
			$profilGlecteurVariable->setGlecteur(key_exists($record['GLecteur'], self::$glecteurs) ? self::$glecteurs[$record['GLecteur']] : null );
			$profilGlecteurVariable->setInstallation($this->installation);
			if($profilGlecteurVariable->getGlecteur() && $profilGlecteurVariable->getVariable() && $profilGlecteurVariable->getProfil()){
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

//        foreach ($records as $offset => $record) {
//            $profilGlecteurVariable = new ProfilGlecteurVariable();
//
//            $profilGlecteurVariable->setProfil(key_exists($record['Profil'], self::$profils) ? self::$profils[$record['Profil']] : null );
//            $profilGlecteurVariable->setVariable(key_exists($record['PHoraire'], self::$variables) ? self::$variables[$record['PHoraire']] : null );
//            $profilGlecteurVariable->setGlecteur(key_exists($record['GLecteur'], self::$glecteurs) ? self::$glecteurs[$record['GLecteur']] : null );
//            $profilGlecteurVariable->setInstallation($this->installation);
//
//            if($profilGlecteurVariable->getGlecteur() && $profilGlecteurVariable->getVariable() && $profilGlecteurVariable->getProfil()){
//                $this->entityManager->persist($profilGlecteurVariable);
//            }
//        }
//        $this->entityManager->flush();
	}
}
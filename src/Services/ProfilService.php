<?php

namespace App\Services;


use App\Entity\Profil;
use Doctrine\ORM\EntityManagerInterface;
use DoctrineBatchUtils\BatchProcessing\SimpleBatchIteratorAggregate;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class ProfilService extends ParserService
{
	public function __construct(ParameterBagInterface $parameterBag, EntityManagerInterface $entityManager, LoggerInterface $logger)
	{
		parent::__construct($parameterBag, $entityManager, $logger);
	}

	public function createInMemory(){
		$records = $this->stmt->process($this->reader);

		foreach ($records as $offset => $record) {
			$profil = new Profil();
			$profil->setInstallation($this->installation);
			$profil->setAppID($record['Numero']);
			$profil->setNom($record['Nom']);
			$profil->setDescription($record['Description']);
			self::$profils[$profil->getAppID()] = $profil;
		}
		self::$profils2 = self::$profils;
		$this->logger->info("**** Création des profils en mémoire = OK | nb : ". count(self::$profils) . " ****");

	}
}
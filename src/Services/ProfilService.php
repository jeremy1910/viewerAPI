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

    public function genProfil(){
        $records = $this->stmt->process($this->reader);
		$iterable = SimpleBatchIteratorAggregate::fromTraversableResult(
			call_user_func(function () use ($records) {
				foreach ($records as $offset => $record) {
					$profil = new Profil();
					$profil->setInstallation($this->installation);
					$profil->setAppID($record['Numero']);
					$profil->setNom($record['Nom']);
					$profil->setDescription($record['Description']);
					self::$profils[$profil->getAppID()] = $profil;
					$this->entityManager->persist($profil);
					yield $offset;
				}
			}),
			$this->entityManager,
			100,
			Profil::class
		);
		foreach ($iterable as $record) {

		}
		$this->logger->info('Profil = OK');

    }
}
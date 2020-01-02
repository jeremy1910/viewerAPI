<?php

namespace App\Services;


use App\Entity\Glecteur;
use Doctrine\ORM\EntityManagerInterface;
use DoctrineBatchUtils\BatchProcessing\SimpleBatchIteratorAggregate;
use League\Csv\Reader;
use League\Csv\Statement;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class GlecteurService extends ParserService
{


    public function __construct(ParameterBagInterface $parameterBag, EntityManagerInterface $entityManager, LoggerInterface $logger)
    {
        parent::__construct($parameterBag, $entityManager, $logger);
    }


    public function genGlecteur(){

        $records = $this->stmt->process($this->reader);

		$iterable = SimpleBatchIteratorAggregate::fromTraversableResult(
			call_user_func(function () use ($records) {
				foreach ($records as $offset => $record) {

					$Glecteur = new Glecteur();
					$Glecteur->setAppID($record['Numero']);
					$Glecteur->setInstallation($this->installation);

					$Glecteur->setNom($this->protectString($record['Nom']));
					$Glecteur->setDescription($this->protectString($record['Description']));
					$this->entityManager->persist($Glecteur);
					self::$glecteurs[$Glecteur->getAppID()] = $Glecteur;
					$this->entityManager->persist($Glecteur);
					yield $offset;
				}
			}),
			$this->entityManager,
			100,
			Glecteur::class
		);
		foreach ($iterable as $record) {}
		$this->logger->info('Glecteur = OK');
    }

}
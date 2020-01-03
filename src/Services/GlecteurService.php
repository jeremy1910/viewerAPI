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


    public function createInMemory(){

        $records = $this->stmt->process($this->reader);

				foreach ($records as $offset => $record) {
					$Glecteur = new Glecteur();
					$Glecteur->setAppID($record['Numero']);
					$Glecteur->setInstallation($this->installation);
					$Glecteur->setNom($this->protectString($record['Nom']));
					$Glecteur->setDescription($this->protectString($record['Description']));
					self::$glecteurs[$Glecteur->getAppID()] = $Glecteur;
				}

		self::$glecteurs2 = self::$glecteurs;
		$this->logger->info("**** Création des groupes de lecteurs en mémoire = OK | nb : ". count(self::$glecteurs) . " ****");
    }

}
<?php
/**
 * Created by PhpStorm.
 * User: jeje
 * Date: 06/10/19
 * Time: 00:40
 */

namespace App\Services;


use App\Entity\Badge;
use App\Entity\Glecteur;
use App\Entity\Variable;
use App\Repository\GlecteurRepository;
use App\Repository\VariableRepository;
use Doctrine\ORM\EntityManagerInterface;
use DoctrineBatchUtils\BatchProcessing\SimpleBatchIteratorAggregate;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class GlecteurDefService extends ParserService
{

	public function __construct(ParameterBagInterface $parameterBag, EntityManagerInterface $entityManager, GlecteurRepository $glecteurRepository, VariableRepository $variableRepository, LoggerInterface $logger)
	{
		parent::__construct($parameterBag, $entityManager, $logger);
		$this->glecteurRepository = $glecteurRepository;
		$this->variableRepository = $variableRepository;

	}

	private $glecteurRepository;
	private $variableRepository;

	public function makeAssociationInMemory(){
		$records = $this->stmt->process($this->reader);

		$batchSize = 100;
		$i = 0;
		foreach ($records as $offset => $record) {
			/**
			 * @var $glecteur Glecteur
			 */
			$glecteur = key_exists($record['GLecteur'], self::$glecteurs) ? self::$glecteurs[$record['GLecteur']] : null ;
			/**
			 * @var $variable Variable
			 */
			$variable = key_exists($record['Variable'], self::$variables) ? self::$variables[$record['Variable']] : null ;
			if($glecteur && $variable){
				unset(self::$glecteurs2[$record['GLecteur']]);
				unset(self::$variables2[$record['Variable']]);
				$variable->addGlecteur($glecteur);
				$this->entityManager->persist($variable);
			}

			if (($i % $batchSize) === 0) {
				$this->entityManager->flush();
				$this->entityManager->clear(Variable::class); // Detaches all objects from Doctrine!
			}
			$i++;
		}
		$this->entityManager->flush(); //Persist objects that did not make up an entire batch
		$this->entityManager->clear(Variable::class);


		$this->logger->info("**** Assocuiation des groupe de lecteur avec les variables en mémoire ****");


	}
}
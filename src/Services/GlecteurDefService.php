<?php
/**
 * Created by PhpStorm.
 * User: jeje
 * Date: 06/10/19
 * Time: 00:40
 */

namespace App\Services;


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

		foreach ($records as $offset => $record) {
			/**
			 * @var $glecteur Glecteur
			 */
			$glecteur = key_exists($record['GLecteur'], self::$glecteurs) ? self::$glecteurs[$record['GLecteur']] : null ;
			$variable = key_exists($record['Variable'], self::$variables) ? self::$variables[$record['Variable']] : null ;
			if($glecteur && $variable){
				unset(self::$glecteurs2[$record['GLecteur']]);
				unset(self::$variables2[$record['Variable']]);
				$glecteur->addVariable($variable);
			}
		}
		$this->logger->info("**** Assocuiation des groupe de lecteur avec les variables en mémoire ****");




//        foreach ($records as $offset => $record) {
//            $glecteur = key_exists($record['GLecteur'], self::$glecteurs) ? self::$glecteurs[$record['GLecteur']] : null ;
//            $variable = key_exists($record['Variable'], self::$variables) ? self::$variables[$record['Variable']] : null ;
//            if($glecteur && $variable){
//                $glecteur->addVariable($variable);
//                $this->entityManager->persist($glecteur);
//            }
//        }
	}
}
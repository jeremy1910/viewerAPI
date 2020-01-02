<?php
/**
 * Created by PhpStorm.
 * User: jeje
 * Date: 06/10/19
 * Time: 00:06
 */

namespace App\Services;


use App\Entity\Variable;
use Doctrine\ORM\EntityManagerInterface;
use DoctrineBatchUtils\BatchProcessing\SimpleBatchIteratorAggregate;
use JMS\Serializer\Serializer;
use JMS\Serializer\SerializerBuilder;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;


class VariableService extends ParserService
{

    public function __construct(ParameterBagInterface $parameterBag, EntityManagerInterface $entityManager, LoggerInterface $logger)
    {
        parent::__construct($parameterBag, $entityManager, $logger);
        $this->serializer = SerializerBuilder::create()->build();
    }

    private $serializer;


    public function genVariable(){

        $records = $this->stmt->process($this->reader);

				foreach ($records as $offset => $record) {

					$variable = new Variable();
					$tranches = [];
					$variable->setInstallation($this->installation);
					$variable->setAppID($record['Numero']);
					$variable->setNom($record['Nom']);
					$variable->setDescription(strstr($record['Description'], '(Null)') ? null : $record['Description']);
					if($record['Extension'] !== '(Null)' AND (preg_match('/^PH.PH[0-9]+$/', $record['Nom']) OR preg_match('/^PH.PF[0-9]+$/', $record['Nom'])))
					{
						$xml = preg_replace('/(<\?xml[^?]+?)utf-16/i', '$1utf-8', $record['Extension']);
						$xml =  str_replace("\\n", "", $xml);
						$xml = simplexml_load_string($xml);
						foreach ($xml->Tranches->anyType as $tranche){
							$tranches[] = ["$tranche->Minute", "$tranche->Valeur"];
						}
					}
					$tranchesSerialized = $this->serializer->serialize($tranches, 'json');
					$variable->setExtension($tranchesSerialized);
					self::$variables[$variable->getAppID()] = $variable;
				}

		$this->logger->info('Variable = OK');



    }
}
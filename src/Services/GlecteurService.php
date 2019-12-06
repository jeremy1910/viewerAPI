<?php
/**
 * Created by PhpStorm.
 * User: jeje
 * Date: 05/10/19
 * Time: 23:16
 */

namespace App\Services;


use App\Entity\Glecteur;
use Doctrine\ORM\EntityManagerInterface;
use League\Csv\Reader;
use League\Csv\Statement;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class GlecteurService extends ParserService
{


    public function __construct(ParameterBagInterface $parameterBag, EntityManagerInterface $entityManager)
    {
        parent::__construct($parameterBag, $entityManager);
    }


    public function genGlecteur(){



        $records = $this->stmt->process($this->reader);


        foreach ($records as $offset => $record) {
            $Glecteur = new Glecteur();

            $Glecteur->setAppID($record['Numero']);
            $Glecteur->setInstallation($this->installation);

            $Glecteur->setNom($this->protectString($record['Nom']));
            $Glecteur->setDescription($this->protectString($record['Description']));
            $this->entityManager->persist($Glecteur);
            self::$glecteurs[$Glecteur->getAppID()] = $Glecteur;

        }

        $this->entityManager->flush();


    }

}
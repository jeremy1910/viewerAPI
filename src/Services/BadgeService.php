<?php
/**
 * Created by PhpStorm.
 * User: jeje
 * Date: 15/10/19
 * Time: 21:29
 */

namespace App\Services;


use App\Entity\Badge;
use App\Entity\Glecteur;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class BadgeService extends ParserService
{
    public function __construct(ParameterBagInterface $parameterBag, EntityManagerInterface $entityManager)
    {
        parent::__construct($parameterBag, $entityManager);


    }


    public function makeAssociation(){



        $records = $this->stmt->process($this->reader);


        foreach ($records as $offset => $record) {

            $badge = new Badge();

            $badge->setAppID($record['Numero']);
            $badge->setMatricule($record['Matricule']);
            $badge->setNom($record['Nom']);

            $badge->setInstallation($this->installation);
            $badge->setMatricule($record['Matricule']);
            $badge->setDateDebVal(\DateTime::createFromFormat('d-m-Y H:i:s', str_replace('/', '-', $record['DateDebVal'])));
            $badge->setDateDebVal2(\DateTime::createFromFormat('d-m-Y H:i:s', str_replace('/', '-', $record['DateDebVal2'])));
            $badge->setDateFinVal(\DateTime::createFromFormat('d-m-Y H:i:s', str_replace('/', '-', $record['DateFinVal'])));
            $badge->setDateFinVal2(\DateTime::createFromFormat('d-m-Y H:i:s', str_replace('/', '-', $record['DateFinVal2'])));
            $badge->setDateCreation(\DateTime::createFromFormat('d-m-Y H:i:s', str_replace('/', '-', $record['DateCreation'])));
            $badge->setCode1($record['Code1']);
            $badge->setPrenom($record['Prenom']);
            $badge->setValide((bool) $record['Valide']);



            $this->entityManager->persist($badge);
            self::$badges[$badge->getAppID()] = $badge;
        }

        $this->entityManager->flush();

    }
}
<?php
/**
 * Created by PhpStorm.
 * User: jeje
 * Date: 06/10/19
 * Time: 01:05
 */

namespace App\Services;


use App\Entity\Profil;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class ProfilService extends ParserService
{
    public function __construct(ParameterBagInterface $parameterBag, EntityManagerInterface $entityManager)
    {
        parent::__construct($parameterBag, $entityManager);
    }


    public function genProfil(){



        $records = $this->stmt->process($this->reader);


        foreach ($records as $offset => $record) {
            $profil = new Profil();
            $profil->setInstallation($this->installation);
            $profil->setAppID($record['Numero']);
            $profil->setNom($record['Nom']);
            $profil->setDescription($record['Description']);
            $this->entityManager->persist($profil);
            self::$profils[$profil->getAppID()] = $profil;
        }

        $this->entityManager->flush();

    }
}
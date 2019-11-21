<?php
/**
 * Created by PhpStorm.
 * User: jeje
 * Date: 15/10/19
 * Time: 22:01
 */

namespace App\Services;



use App\Entity\Badge;
use App\Entity\Profil;
use App\Repository\BadgeRepository;
use App\Repository\ProfilRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class BadgeProfilService extends ParserService
{

    public function __construct(ParameterBagInterface $parameterBag, EntityManagerInterface $entityManager, BadgeRepository $badgeRepository, ProfilRepository $profilRepository, LoggerInterface $logger)
    {
        parent::__construct($parameterBag, $entityManager);
        $this->badgeRepository = $badgeRepository;
        $this->profilRepository = $profilRepository;
        $this->logger = $logger;

    }

    private $badgeRepository;
    private $profilRepository;
    private $logger;

    public function makeAssociation(){



        $records = $this->stmt->process($this->reader);


        foreach ($records as $offset => $record) {


           $badge = key_exists($record['Badge'], self::$badges) ? self::$badges[$record['Badge']] : null ;
           $profil = key_exists($record['Profil'], self::$profils) ? self::$profils[$record['Profil']] : null ;

            //$profil = $this->profilRepository->findOneBy(['appID' =>  (int) $record['Profil']]);

            if($badge && $profil)
            {

                $badge->addProfil($profil);

            }
        }

        $this->entityManager->flush();

    }
}
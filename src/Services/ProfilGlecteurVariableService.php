<?php
/**
 * Created by PhpStorm.
 * User: jeje
 * Date: 06/10/19
 * Time: 10:25
 */

namespace App\Services;


use App\Entity\ProfilGlecteurVariable;
use App\Repository\GlecteurRepository;
use App\Repository\ProfilRepository;
use App\Repository\VariableRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class ProfilGlecteurVariableService extends ParserService
{

    public function __construct(ParameterBagInterface $parameterBag, EntityManagerInterface $entityManager, ProfilRepository $profilRepository, GlecteurRepository $glecteurRepository, VariableRepository $variableRepository)
    {
        parent::__construct($parameterBag, $entityManager);

        $this->profilRepository = $profilRepository;
        $this->glecteurRepository = $glecteurRepository;
        $this->variableRepository = $variableRepository;
    }

    private $profilRepository;
    private $glecteurRepository;
    private $variableRepository;

    public function makeAssociation(){

        $records = $this->stmt->process($this->reader);

        foreach ($records as $offset => $record) {
            $profilGlecteurVariable = new ProfilGlecteurVariable();
            $annomalie = false;
//
//            $profilGlecteurVariable->setProfil($this->profilRepository->findOneBy(['appID' => (int) $record['Profil']]));
//            $profilGlecteurVariable->setGlecteur($this->glecteurRepository->findOneBy(['appID' => (int)$record['GLecteur']]));
//            $profilGlecteurVariable->setVariable($this->variableRepository->findOneBy(['appID' => (int) $record['PHoraire']]));

            $profilGlecteurVariable->setProfil(key_exists($record['Profil'], self::$profils) ? self::$profils[$record['Profil']] : null );
            $profilGlecteurVariable->setVariable(key_exists($record['PHoraire'], self::$variables) ? self::$variables[$record['PHoraire']] : null );
            $profilGlecteurVariable->setGlecteur(key_exists($record['GLecteur'], self::$glecteurs) ? self::$glecteurs[$record['GLecteur']] : null );

            if($profilGlecteurVariable->getGlecteur() && $profilGlecteurVariable->getVariable() && $profilGlecteurVariable->getProfil()){
                $this->entityManager->persist($profilGlecteurVariable);
            }
        }
        $this->entityManager->flush();



    }

}
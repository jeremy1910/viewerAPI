<?php
/**
 * Created by PhpStorm.
 * User: jeje
 * Date: 07/10/19
 * Time: 22:27
 */

namespace App\Services;


use Doctrine\ORM\EntityManagerInterface;

class HandelParserService
{

    public function __construct(GlecteurService $glecteurService, VariableService $variableService, GlecteurDefService $glecteurDefService, ProfilService $profilService, ProfilGlecteurVariableService $profilGlecteurVariableService, EntityManagerInterface $entityManager, BadgeService $badgeService, BadgeProfilService $badgeProfilService)
    {
        $this->glecteurService = $glecteurService;
        $this->variableService = $variableService;
        $this->glecteurDefService = $glecteurDefService;
        $this->profilService = $profilService;
        $this->profilGlecteurVariableService = $profilGlecteurVariableService;
        $this->entityManager = $entityManager;
        $this->badgeService = $badgeService;
        $this->badgeProfilService = $badgeProfilService;

    }

    private $entityManager;
    private $glecteurService;
    private $variableService;
    private $glecteurDefService;
    private $profilService;
    private $profilGlecteurVariableService;
    private $badgeService;
    private $badgeProfilService;

    public function parseFiles(\App\Entity\Installation $installation){

        //$this->trucateTables();

        $this->glecteurService->init('Glecteur', $installation)->genGlecteur();
        $this->variableService->init('Variable', $installation)->genVariable();
        $this->glecteurDefService->init('GlecteurDef', $installation)->makeAssociation();
        $this->profilService->init('Profil', $installation)->genProfil();
        $this->profilGlecteurVariableService->init('ProfilAcces', $installation)->makeAssociation();
        $this->badgeService->init('Badge', $installation)->makeAssociation();
        $this->badgeProfilService->init('BadgeProfil', $installation)->makeAssociation();

    }

    private function trucateTables(){

        $connection = $this->entityManager->getConnection();
        $platform   = $connection->getDatabasePlatform();

        $connection->executeUpdate($platform->getTruncateTableSQL('variable', true /* whether to cascade */));
        $connection->executeUpdate($platform->getTruncateTableSQL('glecteur', true /* whether to cascade */));
        $connection->executeUpdate($platform->getTruncateTableSQL('profil', true /* whether to cascade */));
        $connection->executeUpdate($platform->getTruncateTableSQL('glecteur_variable', true /* whether to cascade */));
        $connection->executeUpdate($platform->getTruncateTableSQL('profil_glecteur_variable', true /* whether to cascade */));
        $connection->executeUpdate($platform->getTruncateTableSQL('badge', true /* whether to cascade */));
        $connection->executeUpdate($platform->getTruncateTableSQL('badge_profil', true /* whether to cascade */));

    }
}
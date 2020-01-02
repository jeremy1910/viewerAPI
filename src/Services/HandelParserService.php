<?php
/**
 * Created by PhpStorm.
 * User: jeje
 * Date: 07/10/19
 * Time: 22:27
 */

namespace App\Services;


use App\Entity\BadgeGlecteurVariable;
use Doctrine\ORM\EntityManagerInterface;

class HandelParserService
{

    public function __construct(GlecteurService $glecteurService, VariableService $variableService, GlecteurDefService $glecteurDefService, ProfilService $profilService, ProfilGlecteurVariableService $profilGlecteurVariableService, EntityManagerInterface $entityManager, BadgeService $badgeService, BadgeProfilService $badgeProfilService, BadgeGlecteurVariableService $badgeGlecteurVariableService)
    {
        $this->glecteurService = $glecteurService;
        $this->variableService = $variableService;
        $this->glecteurDefService = $glecteurDefService;
        $this->profilService = $profilService;
        $this->profilGlecteurVariableService = $profilGlecteurVariableService;
        $this->entityManager = $entityManager;
        $this->badgeService = $badgeService;
        $this->badgeProfilService = $badgeProfilService;
        $this->badgeGlecteurVariableService = $badgeGlecteurVariableService;
    }

    private $entityManager;
    private $glecteurService;
    private $variableService;
    private $glecteurDefService;
    private $profilService;
    private $profilGlecteurVariableService;
    private $badgeService;
    private $badgeProfilService;
    private $badgeGlecteurVariableService;

    public function parseFiles(\App\Entity\Installation $installation){

        //$this->trucateTables();
		$this->badgeService->init('Badge', $installation)->makeAssociation(); //Création des badges en mémoire
		$this->profilService->init('Profil', $installation)->genProfil(); //Création des Profils en mémoire
		$this->glecteurService->init('Glecteur', $installation)->genGlecteur(); // Création des groupes de lecteurs en mémoire
		$this->variableService->init('Variable', $installation)->genVariable();

		$this->profilGlecteurVariableService->init('ProfilAcces', $installation)->makeAssociation();
		$this->badgeGlecteurVariableService->init('BadgeAcces', $installation)->makeAssociation();

		$this->badgeProfilService->init('BadgeProfil', $installation)->makeAssociation();
		$this->glecteurDefService->init('GlecteurDef', $installation)->makeAssociation();

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
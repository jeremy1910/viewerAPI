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

    public function __construct(GlecteurService $glecteurService, VariableService $variableService, GlecteurDefService $glecteurDefService, ProfilService $profilService, ProfilGlecteurVariableService $profilGlecteurVariableService, EntityManagerInterface $entityManager, BadgeService $badgeService, BadgeProfilService $badgeProfilService, BadgeGlecteurVariableService $badgeGlecteurVariableService, PersistAllElementsService $persistAllElementsService)
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
        $this->persistAllElementsService = $persistAllElementsService;
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
    private $persistAllElementsService;

	/**
	 * @param \App\Entity\Installation $installation
	 * @throws \Exception
	 */
    public function parseFiles(\App\Entity\Installation $installation){
		try{
			$this->badgeService->init('Badge', $installation)->createInMemory(); //Création des badges en mémoire
			$this->profilService->init('Profil', $installation)->createInMemory(); //Création des Profils en mémoire
			$this->glecteurService->init('Glecteur', $installation)->createInMemory(); // Création des groupes de lecteurs en mémoire
			$this->variableService->init('Variable', $installation)->createInMemory(); //Création des variables en mémoire

			$this->badgeProfilService->init('BadgeProfil', $installation)->makeAssociationInMemory(); //Association des profils avec les badges
			$this->glecteurDefService->init('GlecteurDef', $installation)->makeAssociationInMemory(); //Association des variable avec les groupe de lecteurs

			/**
			 * Création de la table associative entre Profil -> Groupe de lecteur -> Variable (PH)
			 * Persist et flush de cette table
			 * Ceci va persister egalement toute les groupe de lecteur, variable, et profil qui en découle
			 */
			$this->profilGlecteurVariableService->init('ProfilAcces', $installation)->makeAssociation();

			/**
			 * Création de la table associative entre Badge -> Groupe de lecteur -> Variable (PH)
			 * ce sont les droits individuel
			 * Persist et flush de cette table
			 * Ceci va persister egalement toute les groupe de lecteur, variable, et profil qui en découle
			 */
			$this->badgeGlecteurVariableService->init('BadgeAcces', $installation)->makeAssociation();

			dd('ok');
			$this->persistAllElementsService->shouldPersistAllElement($installation);
		}catch (\Exception $e){
			throw $e;
		}




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
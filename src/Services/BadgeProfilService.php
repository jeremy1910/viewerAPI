<?php

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
		parent::__construct($parameterBag, $entityManager, $logger);
		$this->badgeRepository = $badgeRepository;
		$this->profilRepository = $profilRepository;
		$this->logger = $logger;
	}

	private $badgeRepository;
	private $profilRepository;

	public function makeAssociationInMemory()
	{
		$records = $this->stmt->process($this->reader);
		foreach ($records as $offset => $record) {
			/**
			 * @var $badge Badge
			 */
			$badge = key_exists($record['Badge'], self::$badges) ? self::$badges[$record['Badge']] : null;
			/**
			 * @var $profil Profil
			 */
			$profil = key_exists($record['Profil'], self::$profils) ? self::$profils[$record['Profil']] : null;
			if ($badge && $profil) {
				unset(self::$badges2[$record['Badge']]);
				unset(self::$profils2[$record['Profil']]);
				$profil->addBadge($badge);
			}

		}

		$this->logger->info("**** Assocuiation des profils avec les badge en mémoire ****");

//		$i=0;
//		foreach (self::$badges as $badge){
//			$this->entityManager->persist($badge);
//			if (($i % 100) === 0) {
//				$this->entityManager->flush();
//				$this->entityManager->clear(Badge::class);
//			}
//			$i++;
//		}
//		$this->entityManager->flush();
//		$this->entityManager->clear(Badge::class);
//
//		$i=0;
//		foreach (self::$profils as $profil){
//			$this->entityManager->persist($profil);
//			if (($i % 100) === 0) {
//				$this->entityManager->flush();
//				$this->entityManager->clear(Profil::class);
//			}
//			$i++;
//		}
//		$this->entityManager->flush();
//		$this->entityManager->clear(Profil::class);
	}
}
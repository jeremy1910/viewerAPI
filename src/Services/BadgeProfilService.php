<?php

namespace App\Services;

use App\Entity\Badge;
use App\Entity\Profil;
use App\Repository\BadgeRepository;
use App\Repository\ProfilRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class BadgeProfilService extends ParserService
{
	public function __construct(ParameterBagInterface $parameterBag, EntityManagerInterface $entityManager, BadgeRepository $badgeRepository, ProfilRepository $profilRepository, LoggerInterface $logger, ValidatorInterface $validator)
	{
		parent::__construct($parameterBag, $entityManager, $logger);
		$this->badgeRepository = $badgeRepository;
		$this->profilRepository = $profilRepository;
		$this->logger = $logger;
		$this->validator = $validator;
	}

	private $badgeRepository;
	private $profilRepository;
	private $validator;

	public function makeAssociationInMemory()
	{
		$records = $this->stmt->process($this->reader);

		$batchSize = 100;
		$i = 0;
		foreach ($records as $offset => $record) {
			/**
			 * @var $badge Badge
			 */

			$badge = $this->entityManager->getRepository(Badge::class)->findOneBy(['appID' => $record['Badge'], 'installation' => $this->installation->getId()]);
			if($badge === null){
				$badge = key_exists($record['Badge'], self::$badges) ? self::$badges[$record['Badge']] : null;
			}
			/**
			 * @var $profil Profil
			 */

			$profil = $this->entityManager->getRepository(Profil::class)->findOneBy(['appID' => $record['Profil'], 'installation' => $this->installation->getId()]);
			if($profil === null){
				$profil = key_exists($record['Profil'], self::$profils) ? self::$profils[$record['Profil']] : null;
			}

			if ($badge && $profil) {
				unset(self::$badges2[$record['Badge']]);
				unset(self::$profils2[$record['Profil']]);

				$installation = $this->entityManager->find(\App\Entity\Installation::class, $this->installation->getId());

				$profil->setInstallation($installation);
				$badge->setInstallation($installation);
				$badge->addProfil($profil);

				$this->entityManager->persist($profil);
				$this->entityManager->persist($badge);

			}
			if (($i % $batchSize) === 0) {
				$this->entityManager->flush();
				$this->entityManager->clear();
			}
			$i++;
		}
		$this->entityManager->flush();
		$this->entityManager->clear();
		$this->logger->info("**** Assocuiation des profils avec les badge en mémoire ****");

	}
}
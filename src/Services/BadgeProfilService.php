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
use DoctrineBatchUtils\BatchProcessing\SimpleBatchIteratorAggregate;
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

	public function makeAssociation()
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
				$badge->addProfil($profil);
				$this->entityManager->flush();
			}

		}
		$this->logger->info('BadgePrifilService = OK');
	}
}
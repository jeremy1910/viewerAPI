<?php

namespace App\Repository;

use App\Entity\BadgeGlecteurVariable;
use App\Entity\Installation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\Tools\Pagination\Paginator;

/**
 * @method BadgeGlecteurVariable|null find($id, $lockMode = null, $lockVersion = null)
 * @method BadgeGlecteurVariable|null findOneBy(array $criteria, array $orderBy = null)
 * @method BadgeGlecteurVariable[]    findAll()
 * @method BadgeGlecteurVariable[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BadgeGlecteurVariableRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BadgeGlecteurVariable::class);
    }

	public function findByInstallation(Installation $installation, int $limit, int $start){

		$query = $this->createQueryBuilder('bgv')
			->where('bgv.installation = :installation')
			->setParameter('installation', $installation)
			->setFirstResult($start)
			->setMaxResults($limit);
		$result = $query->getQuery()->getResult();

		$c = count(new Paginator($query, $fetchJoinCollection = true));

		return ['result' => $result, 'countMax' => $c];


	}
}

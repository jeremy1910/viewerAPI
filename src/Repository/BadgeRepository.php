<?php

namespace App\Repository;

use App\Entity\Badge;
use App\Entity\Installation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\Tools\Pagination\Paginator;

/**
 * @method Badge|null find($id, $lockMode = null, $lockVersion = null)
 * @method Badge|null findOneBy(array $criteria, array $orderBy = null)
 * @method Badge[]    findAll()
 * @method Badge[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BadgeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Badge::class);
    }

	/**
	 * @param Installation $installation
	 * @param int $limit
	 * @param int $start
	 * @param bool $withIndivudualRight
	 * @return array
	 */
	public function findByInstallation(Installation $installation, int $limit, int $start, bool $withIndivudualRight = false){
        $query = $this->createQueryBuilder('b');
        if($withIndivudualRight){
			$query->join('b.badgeGlecteurVariable', 'ir');
		}
		$query->where('b.installation = :installation')
            ->setParameter('installation', $installation)
            ->setFirstResult($start)
            ->setMaxResults($limit);
        $result = $query->getQuery()->getResult();

        $c = count(new Paginator($query, $fetchJoinCollection = true));

        return ['result' => $result, 'countMax' => $c];


    }

	/**
	 * @param string $nom
	 * @param Installation $installation
	 * @param int $limit
	 * @param int $start
	 * @param bool $withIndivudualRight
	 * @return array
	 */
	public function findByNom(string $nom, Installation $installation , int $limit, int $start, bool $withIndivudualRight = false){
        $query = $this->createQueryBuilder('b')
            ->where('b.nom LIKE :nom');
        if($withIndivudualRight){
        	$query->join('b.badgeGlecteurVariable', 'ir');
        }
		$query->andwhere('b.installation = :installation')
            ->setParameter('installation', $installation)
            ->setParameter('nom', '%'.$nom.'%')
            ->setFirstResult($start)
            ->setMaxResults($limit);
        $result = $query->getQuery()->getResult();

        $c = count(new Paginator($query, $fetchJoinCollection = true));

        return ['result' => $result, 'countMax' => $c];
    }

	/**
	 * @param string $code1
	 * @param Installation $installation
	 * @param int $limit
	 * @param int $start
	 * @param bool $withIndivudualRight
	 * @return array
	 */
	public function findByCode1(string $code1, Installation $installation , int $limit, int $start, bool $withIndivudualRight = false){
        $query = $this->createQueryBuilder('b')
            ->where('b.code1 LIKE :code1');
		if($withIndivudualRight){
        	$query->join('b.badgeGlecteurVariable', 'ir');
        }
            $query->andwhere('b.installation = :installation')
            ->setParameter('installation', $installation)
            ->setParameter('code1', '%'.$code1.'%')
            ->setFirstResult($start)
            ->setMaxResults($limit);
        $result = $query->getQuery()->getResult();

        $c = count(new Paginator($query, $fetchJoinCollection = true));

        return ['result' => $result, 'countMax' => $c];
    }

	/**
	 * @param string $prenom
	 * @param Installation $installation
	 * @param int $limit
	 * @param int $start
	 * @param bool $withIndivudualRight
	 * @return array
	 */
	public function findByPrenom(string $prenom, Installation $installation , int $limit, int $start, bool $withIndivudualRight = false){
        $query = $this->createQueryBuilder('b')
            ->where('b.prenom LIKE :prenom');
		if($withIndivudualRight){
			$query->join('b.badgeGlecteurVariable', 'ir');
		}
            $query->andwhere('b.installation = :installation')
            ->setParameter('installation', $installation)
            ->setParameter('prenom', '%'.$prenom.'%')
            ->setFirstResult($start)
            ->setMaxResults($limit);
        $result = $query->getQuery()->getResult();

        $c = count(new Paginator($query, $fetchJoinCollection = true));

        return ['result' => $result, 'countMax' => $c];
    }


}

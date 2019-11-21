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

    public function findByInstallation(Installation $installation, int $limit, int $start){
        $query = $this->createQueryBuilder('b')
            ->where('b.installation = :installation')
            ->setParameter('installation', $installation)
            ->setFirstResult($start)
            ->setMaxResults($limit);
        $result = $query->getQuery()->getResult();

        $c = count(new Paginator($query, $fetchJoinCollection = true));

        return ['result' => $result, 'countMax' => $c];


    }

    public function findByNom(string $nom, Installation $installation ,int $limit, int $start){
        $query = $this->createQueryBuilder('b')
            ->where('b.nom LIKE :nom')
            ->andwhere('b.installation = :installation')
            ->setParameter('installation', $installation)
            ->setParameter('nom', '%'.$nom.'%')
            ->setFirstResult($start)
            ->setMaxResults($limit);
        $result = $query->getQuery()->getResult();

        $c = count(new Paginator($query, $fetchJoinCollection = true));

        return ['result' => $result, 'countMax' => $c];
    }

    public function findByCode1(string $code1, Installation $installation ,int $limit, int $start){
        $query = $this->createQueryBuilder('b')
            ->where('b.code1 LIKE :code1')
            ->andwhere('b.installation = :installation')
            ->setParameter('installation', $installation)
            ->setParameter('code1', '%'.$code1.'%')
            ->setFirstResult($start)
            ->setMaxResults($limit);
        $result = $query->getQuery()->getResult();

        $c = count(new Paginator($query, $fetchJoinCollection = true));

        return ['result' => $result, 'countMax' => $c];
    }

    public function findByPrenom(string $prenom, Installation $installation ,int $limit, int $start){
        $query = $this->createQueryBuilder('b')
            ->where('b.prenom LIKE :prenom')
            ->andwhere('b.installation = :installation')
            ->setParameter('installation', $installation)
            ->setParameter('prenom', '%'.$prenom.'%')
            ->setFirstResult($start)
            ->setMaxResults($limit);
        $result = $query->getQuery()->getResult();

        $c = count(new Paginator($query, $fetchJoinCollection = true));

        return ['result' => $result, 'countMax' => $c];
    }



    // /**
    //  * @return Badge[] Returns an array of Badge objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('b.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Badge
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}

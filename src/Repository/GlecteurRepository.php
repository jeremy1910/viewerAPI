<?php

namespace App\Repository;

use App\Entity\Glecteur;
use App\Entity\Installation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\Tools\Pagination\Paginator;

/**
 * @method Glecteur|null find($id, $lockMode = null, $lockVersion = null)
 * @method Glecteur|null findOneBy(array $criteria, array $orderBy = null)
 * @method Glecteur[]    findAll()
 * @method Glecteur[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GlecteurRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Glecteur::class);
    }

    public function findByInstallation(Installation $installation, int $limit, int $start){
        $query = $this->createQueryBuilder('g')
            ->where('g.installation = :installation')
            ->setParameter('installation', $installation)
            ->setFirstResult($start)
            ->setMaxResults($limit);
        $result = $query->getQuery()->getResult();

        $c = count(new Paginator($query, $fetchJoinCollection = true));

        return ['result' => $result, 'countMax' => $c];


    }

    public function findByNom(string $nom, Installation $installation, int $limit, int $start){
        $query = $this->createQueryBuilder('g')
            ->where('g.nom LIKE :nom')
            ->andWhere('g.installation = :installation')
            ->setParameter('nom', '%'.$nom.'%')
            ->setParameter('installation', $installation->getId())
            ->setFirstResult($start)
            ->setMaxResults($limit);
        $result = $query->getQuery()->getResult();
        $c = count(new Paginator($query, $fetchJoinCollection = true));

        return ['result' => $result, 'countMax' => $c];

    }

    // /**
    //  * @return Glecteur[] Returns an array of Glecteur objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('g.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Glecteur
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}

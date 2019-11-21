<?php

namespace App\Repository;

use App\Entity\ProfilGlecteurVariable;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method ProfilGlecteurVariable|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProfilGlecteurVariable|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProfilGlecteurVariable[]    findAll()
 * @method ProfilGlecteurVariable[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProfilGlecteurVariablephRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProfilGlecteurVariable::class);
    }

    // /**
    //  * @return ProfilGlecteurVariableph[] Returns an array of ProfilGlecteurVariableph objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ProfilGlecteurVariableph
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}

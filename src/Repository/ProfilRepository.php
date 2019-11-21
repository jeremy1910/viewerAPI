<?php

namespace App\Repository;

use App\Entity\Installation;
use App\Entity\Profil;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\Tools\Pagination\Paginator;

/**
 * @method Profil|null find($id, $lockMode = null, $lockVersion = null)
 * @method Profil|null findOneBy(array $criteria, array $orderBy = null)
 * @method Profil[]    findAll()
 * @method Profil[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProfilRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Profil::class);
    }

    public function findByInstallation(Installation $installation, int $limit, int $start){
        $query = $this->createQueryBuilder('p')
            ->where('p.installation = :installation')
            ->setParameter('installation', $installation)
            ->setFirstResult($start)
            ->setMaxResults($limit);
        $result = $query->getQuery()->getResult();

        $c = count(new Paginator($query, $fetchJoinCollection = true));

        return ['result' => $result, 'countMax' => $c];


    }

    public function findByNom(string $nom, Installation $installation, int $limit, int $start){
        $query = $this->createQueryBuilder('p')
            ->where('p.nom LIKE :nom')
            ->andWhere('p.installation = :installation')
            ->setParameter('nom', '%'.$nom.'%')
            ->setParameter('installation', $installation->getId())
            ->setFirstResult($start)
            ->setMaxResults($limit);
        $result = $query->getQuery()->getResult();
        $c = count(new Paginator($query, $fetchJoinCollection = true));

        return ['result' => $result, 'countMax' => $c];
    }

    // /**
    //  * @return Profil[] Returns an array of Profil objects
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
    public function findOneBySomeField($value): ?Profil
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

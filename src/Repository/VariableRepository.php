<?php

namespace App\Repository;

use App\Entity\Installation;
use App\Entity\Variable;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\Tools\Pagination\Paginator;

/**
 * @method Variable|null find($id, $lockMode = null, $lockVersion = null)
 * @method Variable|null findOneBy(array $criteria, array $orderBy = null)
 * @method Variable[]    findAll()
 * @method Variable[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VariableRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Variable::class);
    }

    public function findByInstallation(Installation $installation, int $limit, int $start){

        $query = $this->createQueryBuilder('v')
            ->where('v.installation = :installation')
            ->setParameter('installation', $installation)
            ->setFirstResult($start)
            ->setMaxResults($limit);
        $result = $query->getQuery()->getResult();

        $c = count(new Paginator($query, $fetchJoinCollection = true));

        return ['result' => $result, 'countMax' => $c];


    }

    public function findPH()
    {
        $query = $this->createQueryBuilder('v')
            ->where('v.Nom LIKE :nom')
            ->setParameter('nom', 'PH.%');
        return $query->getQuery()->getResult();
    }

    public function findByNom(string $nom, Installation $installation ,int $limit, int $start){
        $query = $this->createQueryBuilder('v')
            ->where('v.Nom LIKE :nom')
            ->andWhere('v.installation = :installation')
            ->setParameter('nom', '%'.$nom.'%')
            ->setParameter('installation', $installation->getId())
            ->setFirstResult($start)
            ->setMaxResults($limit);
        $result = $query->getQuery()->getResult();
        $c = count(new Paginator($query, $fetchJoinCollection = true));

        return ['result' => $result, 'countMax' => $c];
    }

    public function findByDescription(string $description, Installation $installation, int $limit, int $start){
        $query = $this->createQueryBuilder('v')
            ->where('v.description LIKE :description')
            ->andWhere('v.installation = :installation')
            ->setParameter('description', '%'.$description.'%')
            ->setParameter('installation', $installation->getId())
            ->setFirstResult($start)
            ->setMaxResults($limit);
        $result = $query->getQuery()->getResult();
        $c = count(new Paginator($query, $fetchJoinCollection = true));

        return ['result' => $result, 'countMax' => $c];
    }

    // /**
    //  * @return Variable[] Returns an array of Variable objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('v.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Variable
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}

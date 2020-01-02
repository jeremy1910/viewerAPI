<?php

namespace App\Repository;

use App\Entity\Installation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Installation|null find($id, $lockMode = null, $lockVersion = null)
 * @method Installation|null findOneBy(array $criteria, array $orderBy = null)
 * @method Installation[]    findAll()
 * @method Installation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class InstallationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Installation::class);
    }

	public function deleteAllContentByInstallationId(Installation $installation)
	{
		$conn = $this->getEntityManager()->getConnection();
		$id = $installation->getId();
		$sql = '
        delete from badge_glecteur_variable where installation_id = :id;
		delete from profil_glecteur_variable where installation_id = :id;
		delete from badge where installation_id = :id;
		delete from glecteur where installation_id = :id;
		delete from profil where installation_id = :id;
		delete from variable where installation_id = :id;
		delete from installation where id = :id;
        ';
		$stmt = $conn->prepare($sql);
		$stmt->execute(['id' => $id]);

	}


    // /**
    //  * @return Installation[] Returns an array of Installation objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('i.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Installation
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}

<?php

namespace App\Repository;

use App\Entity\TraitWorld;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TraitWorld|null find($id, $lockMode = null, $lockVersion = null)
 * @method TraitWorld|null findOneBy(array $criteria, array $orderBy = null)
 * @method TraitWorld[]    findAll()
 * @method TraitWorld[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TraitWorldRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TraitWorld::class);
    }

    // /**
    //  * @return TraitWorld[] Returns an array of TraitWorld objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?TraitWorld
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}

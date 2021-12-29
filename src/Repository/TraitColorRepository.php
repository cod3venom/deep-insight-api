<?php

namespace App\Repository;

use App\Entity\HumanTraits\TraitColor;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TraitColor|null find($id, $lockMode = null, $lockVersion = null)
 * @method TraitColor|null findOneBy(array $criteria, array $orderBy = null)
 * @method TraitColor[]    findAll()
 * @method TraitColor[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TraitColorRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TraitColor::class);
    }

    // /**
    //  * @return TraitColor[] Returns an array of TraitColor objects
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
    public function findOneBySomeField($value): ?TraitColor
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

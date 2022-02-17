<?php

namespace App\Repository;

use App\Entity\HumanTraits\TraitWorld;
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

}

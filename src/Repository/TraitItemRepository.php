<?php

namespace App\Repository;

use App\Entity\HumanTraits\TraitCategory;
use App\Entity\HumanTraits\TraitItem;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TraitItem|null find($id, $lockMode = null, $lockVersion = null)
 * @method TraitItem|null findOneBy(array $criteria, array $orderBy = null)
 * @method TraitItem[]    findAll()
 * @method TraitItem[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TraitItemRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TraitItem::class);
    }

    /**
     * Return array of the
     * Trait items which
     * already be mapped to the
     * trait categories.
     * @return array
     */
    public function all(): array
    {
        return $this->createQueryBuilder('q')
            ->getQuery()
            ->getResult();
    }

}

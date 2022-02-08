<?php

namespace App\Repository;

use App\Entity\HumanTraits\TraitColor;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\AbstractQuery;
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

    public function findColorByWorldName(string $worldName): ?string
    {
        $obj =  $this->createQueryBuilder('t')
            ->andWhere('LOWER(t.name) = :worldName')
            ->setParameter('worldName', strtolower($worldName))
            ->setMaxResults(1)
            ->getQuery()
            ->getResult(AbstractQuery::HYDRATE_OBJECT);

        if (is_array($obj) && count($obj) > 0) {
            $obj = $obj[0];
        }

        if (!($obj instanceof TraitColor)) {
            return "";
        }
        if (!$obj->getColor()) {
            return "";
        }
        return $obj->getColor();
    }
}

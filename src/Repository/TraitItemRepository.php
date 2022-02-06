<?php

namespace App\Repository;

use App\Entity\HumanTraits\TraitCategory;
use App\Entity\HumanTraits\TraitItem;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\AbstractQuery;
use Doctrine\Persistence\ManagerRegistry;
use phpDocumentor\Reflection\Types\Array_;

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

    public function findIconByTraitName(string $traitName): ?string
    {
        $obj =  $this->createQueryBuilder('t')
            ->andWhere('LOWER(t.name) = :traitName')
            ->setParameter('traitName', strtolower($traitName))
            ->setMaxResults(1)
            ->getQuery()
            ->getResult(AbstractQuery::HYDRATE_OBJECT);

        if (is_array($obj) && count($obj) > 0) {
            $obj = $obj[0];
        }

        if (!($obj instanceof TraitItem)) {
            return "";
        }
        if (!$obj->getIcon()) {
            return "";
        }
        return 'http://localhost:8000/assets/traits/icons/'. $obj->getIcon();
    }

}

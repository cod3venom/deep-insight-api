<?php

namespace App\Repository;

use App\Entity\HumanTraits\TraitCategory;
use App\Entity\HumanTraits\TraitItem;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
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
        return $_ENV['BACKEND_ASSETS'] . '/traits/icons/' . $obj->getIcon();
    }

    /**
     * @throws OptimisticLockException
     * @throws ORMException
     */
    public function save(TraitItem $item)
    {
        $this->_em->persist($item);
        $this->_em->flush();
    }

    /**
     * @param TraitItem $item
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function update(TraitItem $item)
    {
        $this->_em->flush($item);
    }

    /**
     * @param TraitItem $item
     * @return void
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function delete(TraitItem $item)
    {
        $this->_em->remove($item);
        $this->_em->flush();
    }
}

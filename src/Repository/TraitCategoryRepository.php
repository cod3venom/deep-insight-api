<?php

namespace App\Repository;

use App\Entity\HumanTraits\TraitCategory;
use App\Entity\HumanTraits\TraitItem;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Uid\UuidV4;

/**
 * @method TraitCategory|null find($id, $lockMode = null, $lockVersion = null)
 * @method TraitCategory|null findOneBy(array $criteria, array $orderBy = null)
 * @method TraitCategory[]    findAll()
 * @method TraitCategory[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TraitCategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TraitCategory::class);
    }

    /**
     * @throws NonUniqueResultException
     * @throws NoResultException
     */
    public function findById(string $categoryId): TraitCategory
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.categoryId = :categoryId')
            ->setParameter('categoryId', $categoryId)
            ->setMaxResults(1)
            ->getQuery()
            ->getSingleResult(AbstractQuery::HYDRATE_OBJECT);
    }
    /**
     * @param string $name
     * @return TraitCategory
     * @throws NonUniqueResultException
     * @throws NoResultException
     */
    public function findByName(string $name): TraitCategory
    {
        return $this->createQueryBuilder('c')
            ->andWhere('LOWER(c.categoryName) = :name')
            ->setParameter('name', strtolower($name))
            ->setMaxResults(1)
            ->getQuery()
            ->getSingleResult(AbstractQuery::HYDRATE_OBJECT);
    }

    public function getAllCategoryNames(): array {
        return $this->createQueryBuilder('c')
            ->select('c.categoryId, c.categoryName')
            ->getQuery()
            ->getResult(AbstractQuery::HYDRATE_OBJECT);
    }

    public function save(TraitItem $item)
    {
       $this->_em->persist($item);
       $this->_em->flush();
    }
}

<?php

namespace App\Repository;

use App\Entity\HumanTraits\TraitAnalysis;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TraitAnalysis|null find($id, $lockMode = null, $lockVersion = null)
 * @method TraitAnalysis|null findOneBy(array $criteria, array $orderBy = null)
 * @method TraitAnalysis[]    findAll()
 * @method TraitAnalysis[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TraitAnalysisRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TraitAnalysis::class);
    }

    /**
     * @param $value
     * @return TraitAnalysis
     */
    public function findTraitsByBirthDay($value): TraitAnalysis
    {
        try{
            return $this->createQueryBuilder('t')
                ->andWhere('t.birthDay = :birthDay')
                ->setParameter('birthDay', $value)
                ->setMaxResults(1)
                ->getQuery()
                ->getSingleResult()
                ;
        }
        catch (\Exception $ex){
            return new TraitAnalysis();
        }
    }

}

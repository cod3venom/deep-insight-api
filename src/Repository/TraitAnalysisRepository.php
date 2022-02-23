<?php

namespace App\Repository;

use App\Entity\HumanTraits\TraitAnalysis;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use Exception;
use Psr\Log\LoggerInterface;

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
     * @param string|null $birthDay
     * @return TraitAnalysis
     */
    public function findTraitsByBirthDay(?string $birthDay): TraitAnalysis
    {
        try{
            $birthDay = (string) $birthDay;
            $result =  $this->createQueryBuilder('analysis')
                ->andWhere("DATE_FORMAT(cast(analysis.birthDay as date), '%d/%m/%Y') = DATE_FORMAT(cast(:birthDay as date), '%d/%m/%Y')")
                ->setParameter('birthDay', $birthDay)
                ->setMaxResults(1)
                ->getQuery();

            return $result->getSingleResult();
        }
        catch (Exception $ex){
            return new TraitAnalysis();
        }
    }
	
	
	/**
	 * @param string $worldColumn
	 * @return QueryBuilder
	 */
    public function filterTraitsByWorld(string $worldColumn): QueryBuilder
    {
		return $this->createQueryBuilder('analysis')
			->andWhere("DATE_FORMAT(cast(analysis.birthDay as date), '%d/%m/%Y') = DATE_FORMAT(cast(:birthDay as date), '%d/%m/%Y')")
			->orderBy('analysis.'.$worldColumn, 'DESC');
    }

    /**
     * @return EntityManagerInterface
     */
    public function getEntityManager():EntityManagerInterface
    {
        return $this->_em;
    }

    /**
     * @param TraitAnalysis $item
     * @return void
     */
    public function save(TraitAnalysis $item)
    {
        $this->_em->persist($item);
        $this->_em->flush();
    }

    /**
     * @param TraitAnalysis $item
     * @return void
     */
    public function update(TraitAnalysis $item)
    {
        $this->_em->flush($item);
    }

    /**
     * @param TraitAnalysis $item
     * @return void
     */
    public function delete(TraitAnalysis $item)
    {
        $this->_em->remove($item);
        $this->_em->flush();
    }

}

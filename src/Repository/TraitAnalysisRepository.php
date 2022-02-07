<?php

namespace App\Repository;

use App\Entity\HumanTraits\TraitAnalysis;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
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
    private LoggerInterface $logger;
    public function __construct(ManagerRegistry $registry, LoggerInterface $logger)
    {
        parent::__construct($registry, TraitAnalysis::class);
        $this->logger = $logger;
    }

    /**
     * @param $value
     * @return TraitAnalysis
     */
    public function findTraitsByBirthDay(?string $birthDay): TraitAnalysis
    {
        try{
            $birthDay = (string) $birthDay;
            $result =  $this->createQueryBuilder('t')
                ->andWhere('t.birthDay = :birthDay')
                ->setParameter('birthDay', $birthDay)
                ->setMaxResults(1)
                ->getQuery();
            $sql  = $result->getSQL();

            $this->logger->debug('SEARCHING FOR', [$sql, ['birthDay' => $birthDay]]);
            return $result->getSingleResult()
                ;
        }
        catch (Exception $ex){
            return new TraitAnalysis();
        }
    }

    /**
     * @param TraitAnalysis $item
     * @return void
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function save(TraitAnalysis $item)
    {
        $this->_em->persist($item);
        $this->_em->flush();
    }

    /**
     * @param TraitAnalysis $item
     * @return void
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function update(TraitAnalysis $item)
    {
        $this->_em->flush($item);
    }

    /**
     * @param TraitAnalysis $item
     * @return void
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function delete(TraitAnalysis $item)
    {
        $this->_em->remove($item);
        $this->_em->flush();
    }

}

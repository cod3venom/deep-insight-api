<?php

namespace App\Repository;

use App\Entity\User\UserCompanyInfo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method UserCompanyInfo|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserCompanyInfo|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserCompanyInfo[]    findAll()
 * @method UserCompanyInfo[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserCompanyInfoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserCompanyInfo::class);
    }

    /**
     * @param UserCompanyInfo $companyInfo
     * @return void
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function save(UserCompanyInfo $companyInfo)
    {
        $this->_em->persist($companyInfo);
        $this->_em->flush();
    }
}

<?php

namespace App\Repository;

use App\Entity\Contact\ContactCompany;
use App\Entity\User\UserProfile;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ContactCompany|null find($id, $lockMode = null, $lockVersion = null)
 * @method ContactCompany|null findOneBy(array $criteria, array $orderBy = null)
 * @method ContactCompany[]    findAll()
 * @method ContactCompany[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ContactCompanyRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ContactCompany::class);
    }

    /**
     * @param ContactCompany $company
     * @return void
     * @throws ORMException
     */
    public function save(ContactCompany $company)
    {
        $this->_em->persist($company);
        $this->_em->flush();
    }

    /**
     * @param ContactCompany $company
     * @return void
     */
    public function update(ContactCompany $company)
    {
        $this->_em->flush();
    }

    /**
     * @param ContactCompany $company
     * @return void
     * @throws ORMException
     */
    public function delete(ContactCompany $company)
    {
        $this->_em->remove($company);
        $this->_em->flush();
    }
}

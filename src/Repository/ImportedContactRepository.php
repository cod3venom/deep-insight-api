<?php

namespace App\Repository;

use App\Entity\Contact\ImportedContact;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ImportedContact|null find($id, $lockMode = null, $lockVersion = null)
 * @method ImportedContact|null findOneBy(array $criteria, array $orderBy = null)
 * @method ImportedContact[]    findAll()
 * @method ImportedContact[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ImportedContactRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ImportedContact::class);
    }

    /**
     * @param ImportedContact $user
     */
    public function save(ImportedContact $user)
    {
        $this->_em->persist($user);
        $this->_em->flush();
    }

    /**
     * @param ImportedContact $user
     */
    public function update(ImportedContact $user)
    {
        $this->_em->flush();
    }

    /**
     * @param ImportedContact $profile
     * @return void
     */
    public function delete(ImportedContact $profile)
    {
        $this->_em->remove($profile);
        $this->_em->flush();
    }
}

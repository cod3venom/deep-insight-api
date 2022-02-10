<?php

namespace App\Repository;

use App\Entity\User\ImportedSubUsers;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ImportedSubUsers|null find($id, $lockMode = null, $lockVersion = null)
 * @method ImportedSubUsers|null findOneBy(array $criteria, array $orderBy = null)
 * @method ImportedSubUsers[]    findAll()
 * @method ImportedSubUsers[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ImportedSubUsersRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ImportedSubUsers::class);
    }

    /**
     * @param ImportedSubUsers $user
     * @throws OptimisticLockException
     * @throws ORMException
     */
    public function save(ImportedSubUsers $user)
    {
        $this->_em->persist($user);
        $this->_em->flush();
    }

    /**
     * @throws OptimisticLockException
     * @throws ORMException
     */
    public function update(ImportedSubUsers $user)
    {
        $this->_em->flush();
    }

    /**
     * @param ImportedSubUsers $profile
     * @return void
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function delete(ImportedSubUsers $profile)
    {
        $this->_em->remove($profile);
        $this->_em->flush();
    }
}

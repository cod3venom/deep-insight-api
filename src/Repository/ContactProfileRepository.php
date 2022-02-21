<?php

namespace App\Repository;

use App\Entity\Contact\ContactProfile;
use App\Entity\User\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ContactProfile|null find($id, $lockMode = null, $lockVersion = null)
 * @method ContactProfile|null findOneBy(array $criteria, array $orderBy = null)
 * @method ContactProfile[]    findAll()
 * @method ContactProfile[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ContactProfileRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ContactProfile::class);
    }

    /**
     * @throws NonUniqueResultException
     * @throws NoResultException
     */
    public function isContactExists(User $owner, ContactProfile $contactProfile): bool
    {
        $total = $this->createQueryBuilder('c')
            ->select('count(c.id)')
            ->where('c.firstName = :firstName')
            ->andWhere('c.lastName = :lastName')
            ->andWhere('c.birthDay = :birthDay')
            ->andWhere('c.ownerUserId = :ownerUserId')
            ->setParameter('firstName', $contactProfile->getFirstName())
            ->setParameter('lastName', $contactProfile->getLastName())
            ->setParameter('ownerUserId', $owner->getUserId())
            ->getQuery()
            ->getSingleScalarResult();

        return ($total > 0);
    }

    public function create(User $owner, array $contactProfile, array $contactCompany) {
        return [];

    }
    public function getEntityManager(): EntityManagerInterface
    {
        return $this->_em;
    }

    /**
     * @param ContactProfile $contactProfile
     * @return void
     * @throws ORMException
     */
    public function save(ContactProfile $contactProfile)
    {
        $this->_em->persist($contactProfile);
        $this->_em->flush();
    }

    /**
     * @param ContactProfile $contactProfile
     * @return void
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function update(ContactProfile $contactProfile)
    {
        $this->_em->flush();
    }

    /**
     * @param ContactProfile $contactProfile
     * @return void
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function delete(ContactProfile $contactProfile)
    {
        $this->_em->remove($contactProfile);
        $this->_em->flush();
    }
}

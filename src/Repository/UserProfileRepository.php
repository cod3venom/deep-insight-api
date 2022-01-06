<?php

namespace App\Repository;

use App\Entity\User\User;
use App\Entity\User\UserProfile;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method UserProfile|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserProfile|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserProfile[]    findAll()
 * @method UserProfile[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserProfileRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserProfile::class);
    }

    public function getProfile(string $userId){
        return $this->createQueryBuilder('p')
            ->select('p')
            ->innerJoin(User::class, 'u', 'WITH', 'p.userId = u.userId')
            ->where('p.userId = :userId')
            ->setParameter('userId', $userId)
            ->setMaxResults(1)
            ->getQuery()
            ->getResult();
    }

    public function all()
    {
        return $this->createQueryBuilder('p')
            ->select('p.userId, u.userAuthorId, p.firstName, p.lastName, p.email, p.phone, p.birthDay, p.avatar, u.roles, u.lastLoginAt, p.createdAt')
            ->innerJoin(User::class, 'u', 'WITH', 'p.userId = u.userId')
            ->getQuery()
            ->getResult();
    }

    public function allUsers()
    {
        return $this->createQueryBuilder('p')
            ->select('p.userId, u.userAuthorId, p.firstName, p.lastName, p.email, p.phone, p.birthDay, p.avatar, u.roles, u.lastLoginAt, p.createdAt')
            ->innerJoin(User::class, 'u', 'WITH', 'p.userId = u.userId')
            ->where('u.roles LIKE :roles')
            ->setParameter('roles', '%"' . User::ROLE_USER . '"%')
            ->getQuery()
            ->getResult();
    }

    public function allSubUsers()
    {
        return $this->createQueryBuilder('p')
            ->select('p.userId, u.userAuthorId, p.firstName, p.lastName, p.email, p.phone, p.birthDay, p.avatar, u.roles, u.lastLoginAt, p.createdAt')
            ->innerJoin(User::class, 'u', 'WITH', 'p.userId = u.userId')
            ->where('u.roles LIKE :roles')
            ->setParameter('roles', '%"' . User::ROLE_SUB_USER . '"%')
            ->getQuery()
            ->getResult();
    }

    /**
     * @param string $subUserId
     * @return mixed
     */
    public function findSubUserById(string $subUserId): mixed
    {
        return $this->createQueryBuilder('p')
            ->select('p.userId, u.userAuthorId, p.firstName, p.lastName, p.email, p.phone, p.birthDay, p.avatar, u.roles, u.lastLoginAt, p.createdAt')
            ->innerJoin(User::class, 'u', 'WITH', 'p.userId = u.userId')
            ->where('u.roles LIKE :roles')
            ->andWhere('u.userId = :subUserId')
            ->setParameter('roles', '%"' . User::ROLE_SUB_USER . '"%')
            ->setParameter('subUserId', $subUserId)
            ->getQuery()
            ->getResult();
    }

    /**
     * @param UserProfile $profile
     * @return void
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function save(UserProfile $profile)
    {
        $this->_em->persist($profile);
        $this->_em->flush();
    }
}

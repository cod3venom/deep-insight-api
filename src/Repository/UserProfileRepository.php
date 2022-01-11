<?php

namespace App\Repository;

use App\Entity\User\User;
use App\Entity\User\UserProfile;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;
use Exception;

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

    /**
     * @param string $email
     * @return bool
     */
    public function exists(string $email): bool {
        try {
            return !!$this->findByEmail($email)->getUserId();
        }
        catch (Exception $ex){
            return false;
        }
    }

    public function existBySubUserId(string $subUserId): bool {
        try {
            return !!$this->findSubUserById($subUserId)->getUserId();
        }
        catch (Exception $ex){
            return false;
        }
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


    /**
     * @throws NonUniqueResultException
     * @throws NoResultException
     */
    public function findSubUserById(string $subUserId): UserProfile{
        $result =  $this->createQueryBuilder('p')
            ->andWhere('p.id = :subUserId')
            ->setParameter('subUserId', $subUserId)
            ->setMaxResults(1)
            ->getQuery()
            ->getResult();
        return (new UserProfile())->arrayToProfile($result);
    }


    /**
     * @param string $email
     * @return mixed
     * @throws NoResultException
     * @throws NonUniqueResultException
     */
    public function findByEmail(string $email): UserProfile
    {
        return $this->createQueryBuilder('p')
            ->select('p.userId, u.userAuthorId, p.firstName, p.lastName, p.email, p.phone, p.birthDay, p.avatar, u.roles, u.lastLoginAt, p.createdAt')
            ->innerJoin(User::class, 'u', 'WITH', 'p.userId = u.userId')
            ->where('u.roles LIKE :roles')
            ->andWhere('u.email = :email')
            ->setParameter('roles', '%"' . User::ROLE_SUB_USER . '"%')
            ->setParameter('email', $email)
            ->getQuery()
            ->getSingleResult();
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

    /**
     * @param UserProfile $profile
     * @return void
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function update(UserProfile $profile)
    {
        $this->_em->flush();
    }
    /**
     * @param UserProfile $profile
     * @return void
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function delete(UserProfile $profile)
    {
        $this->_em->remove($profile);
        $this->_em->flush();
    }
}

<?php

namespace App\Repository;

use App\Entity\User\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;
use Exception;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * @param string $email
     * @return bool
     */
    public function exists(string $email): bool {
        try {
            $user  = $this->findByEmail($email);
            $flag = !empty($user->getUserId());
            return $flag;
        }
        catch (Exception $ex){
            return false;
        }
    }

    /**
     * @param string $userId
     * @return bool
     */
    public function existByUserId(string $userId): bool {
        try {
            return !!$this->findUserById($userId)->getUserId();
        }
        catch (Exception $ex){
            return false;
        }
    }

    /**
     * @param string $userId
     * @return bool
     */
    public function existsAsSubUser(string $userId): bool
    {
        try {
            return !!$this->findSubUserById($userId)->getUserId();
        }
        catch (Exception $ex){
            return false;
        }
    }


    /**
     * @throws NonUniqueResultException
     */
    public function findByEmail(string $email): User
    {
        try{
            return $this->createQueryBuilder('u')
                ->andWhere('LOWER(u.email) = :email')
                ->setParameter('email', strtolower($email))
                ->setMaxResults(1)
                ->getQuery()
                ->getSingleResult();
        }
        catch (NoResultException) {
            return new User();
        }
    }

    /**
     * @throws NonUniqueResultException
     */
    public function findUserById(string $id): User
    {
        try{
            return $this->createQueryBuilder('u')
                ->andWhere('u.userId = :userId')
                ->setParameter('userId', $id)
                ->setMaxResults(1)
                ->getQuery()
                ->getSingleResult();
        }
        catch (NoResultException) {
            return new User();
        }
    }

    /**
     * @throws NonUniqueResultException
     */
    public function findSubUserById(string $subUserId): User
    {
        try{
            return $this->createQueryBuilder('u')
                    ->where('u.roles LIKE :roles')
                    ->andWhere('u.userId = :subUserId')
                    ->setParameter('roles', '%"' . User::ROLE_SUB_USER . '"%')
                    ->setParameter('subUserId', $subUserId)
                    ->setMaxResults(1)
                    ->getQuery()
                    ->getSingleResult();
        }
        catch (NoResultException) {
            return new User();
        }
    }

    /**
     * Returns list of sub-users
     * @return array
     */
    public function allSubUsers(): array
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.roles LIKE :roles')
            ->setParameter('roles', '%"' . User::ROLE_SUB_USER . '"%')
            ->getQuery()
            ->getResult();
    }

    /**
     * @throws OptimisticLockException
     * @throws ORMException
     */
    public function save(User $user)
    {
        $this->_em->persist($user);
        $this->_em->flush();
    }

    /**
     * @throws OptimisticLockException
     * @throws ORMException
     */
    public function update(User $user)
    {
        $this->_em->flush();
    }

    /**
     * @param User $profile
     * @return void
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function delete(User $profile)
    {
        $this->_em->remove($profile);
        $this->_em->flush();
    }
}

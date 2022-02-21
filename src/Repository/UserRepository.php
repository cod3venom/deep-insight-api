<?php

namespace App\Repository;

use App\Controller\API\User\Exceptions\UserAlreadyExistsException;
use App\Controller\API\User\Exceptions\UserRepeatedPasswordMatchingException;
use App\DAO\UserPackTObject;
use App\Entity\Contact\ContactCompany;
use App\Entity\Contact\ContactProfile;
use App\Entity\HumanTraits\TraitAnalysis;
use App\Entity\User\User;
use App\Entity\User\UserProfile;
use App\Service\HumanTraitServices\Helpers\SchemaBuilder\SchemaBuilder;
use App\Service\HumanTraitServices\HumanTraitsService;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\Query\ResultSetMapping;
use Doctrine\ORM\QueryBuilder;
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
    public function __construct(ManagerRegistry $registry,)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * @param string|null $email
     * @return bool
     * @throws UserAlreadyExistsException
     * @throws NonUniqueResultException
     */
    public function exists(?string $email): bool {
        if (!$email) {
            return false;
        }
        $user  = $this->findByEmail($email);

        if (!empty($user->getUserId())) {
            throw new UserAlreadyExistsException('User already exists');
        }
        return false;
    }

    /**
     * @param string $email
     * @return User
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
     * @throws NoResultException
     */
    public function findUserById(string $userId) {
        return $this->createQueryBuilder('user')
            ->andWhere('user.userId = :userId')
            ->setParameter('userId', $userId)
            ->setMaxResults(1)
            ->getQuery()
            ->getSingleResult();
    }


    /**
     * @throws UserRepeatedPasswordMatchingException
     */
    public function verifyRepeatedPassword(string $password, string $repeated): bool
    {
        if ($password !== $repeated) {
            throw new UserRepeatedPasswordMatchingException("Provided passwords didn't match");
        }
        return true;
    }

    /**
     * @return EntityManagerInterface
     */
    public function getEntityManager(): EntityManagerInterface
    {
        return $this->_em;
    }

    /**
     * @param User $user
     */
    public function save(User $user)
    {
        $this->_em->persist($user);
        $this->_em->flush();
    }

    /**
     * @param User $user
     */
    public function update(User $user)
    {
        $this->_em->flush();
    }

    /**
     * @param User $profile
     * @return void
     */
    public function delete(User $profile)
    {
        $this->_em->remove($profile);
        $this->_em->flush();
    }
}

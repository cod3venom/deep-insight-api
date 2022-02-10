<?php

namespace App\Repository;

use App\Entity\User\User;
use App\Entity\User\UserCompanyInfo;
use App\Entity\User\UserProfile;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;
use Exception;
use Psr\Log\LoggerInterface;

/**
 * @method UserProfile|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserProfile|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserProfile[]    findAll()
 * @method UserProfile[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserProfileRepository extends ServiceEntityRepository
{
    private LoggerInterface $logger;
    public function __construct(ManagerRegistry $registry, LoggerInterface $logger)
    {
        parent::__construct($registry, UserProfile::class);
        $this->logger = $logger;
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
            return !!$this->findSubUsersById($subUserId)->getUserId();
        }
        catch (Exception $ex){
            return false;
        }
    }

    public function getProfile(string $userId){
        return $this->createQueryBuilder('p')
            ->andWhere('p.userId = :userId')
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
     * @param string $subUserId
     * @return UserProfile
     * @throws NonUniqueResultException
     */
    public function findSubUserById(string $subUserId): UserProfile{
        try{
            return $this->createQueryBuilder('p')
                ->andWhere('p.userId = :subUserId')
                ->setParameter('subUserId', $subUserId)
                ->setMaxResults(1)
                ->getQuery()
                ->getSingleResult();
        }
        catch (NoResultException) {
            return new UserProfile();
        }
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
     * @param string $keyword
     * @return array
     */
    public function searchForSubUser(string $keyword): array
    {
        try{

            $this->logger->info('searchForSubUser', ['keyword' => $keyword]);
            return $this->createQueryBuilder('p')
                ->select('u')
                ->innerJoin(User::class, 'u', 'WITH', 'u.userId = p.userId')
                ->innerJoin(UserCompanyInfo::class, 'c', 'WITH', 'c.userId = p.userId')

                ->andWhere('u.roles LIKE :roles')
                ->andWhere('LOWER(p.firstName) LIKE :keyword')
                ->orWhere('LOWER(p.lastName) LIKE :keyword')
                ->orWhere('LOWER(p.email) LIKE :keyword')
                ->orWhere('LOWER(p.placeOfBirth) LIKE :keyword')
                ->orWhere('LOWER(p.linksToProfiles) LIKE :keyword')
                ->orWhere('LOWER(p.notesDescriptionsComments) LIKE :keyword')
                ->orWhere('LOWER(p.country) LIKE :keyword')

                ->orWhere('LOWER(c.companyName) LIKE :keyword')
                ->orWhere('LOWER(c.companyWww) LIKE :keyword')
                ->orWhere('LOWER(c.companyIndustry) LIKE :keyword')
                ->orWhere('LOWER(c.wayToEarnMoney) LIKE :keyword')
                ->orWhere('CAST(c.regon as string) LIKE :keyword')
                ->orWhere('CAST(c.krs as string) LIKE :keyword')
                ->orWhere('CAST(c.nip as string) LIKE :keyword')
                ->orWhere('CAST(c.districts as string) LIKE :keyword')
                ->orWhere('CAST(c.headQuartersCity as string) LIKE :keyword')
                ->orWhere('CAST(c.businessEmails as string) LIKE :keyword')
                ->orWhere('CAST(c.businessPhones as string) LIKE :keyword')
                ->orWhere('CAST(c.revenue as string) LIKE :keyword')
                ->orWhere('CAST(c.profit as string) LIKE :keyword')
                ->orWhere('CAST(c.growthYearToYear as string) LIKE :keyword')
                ->orWhere('CAST(c.categories as string) LIKE :keyword')


                ->setParameter('roles', '%"' . User::ROLE_SUB_USER . '"%')
                ->setParameter('keyword', '%' . strtolower($keyword). '%')

                ->getQuery()
                ->getResult();

        }
        catch (Exception $e) {
            $this->logger->error('searchForSubUser', [$e]);
            return[];
        }
    }

    public function filterByWorlds() {

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

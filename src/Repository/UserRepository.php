<?php

namespace App\Repository;

use App\Controller\API\User\Exceptions\UserAlreadyExistsException;
use App\Controller\API\User\Exceptions\UserRepeatedPasswordMatchingException;
use App\Entity\User\User;
use App\Service\HumanTraitServices\HumanTraitsService;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\AbstractQuery;
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
    /**
     * @var TraitAnalysisRepository
     */
    private TraitAnalysisRepository $traitAnalysisRepository;

    /**
     * @var TraitItemRepository
     */
    private TraitItemRepository $traitItemRepository;

    /**
     * @var TraitColorRepository
     */
    private TraitColorRepository $traitColorRepository;

    /**
     * @var HumanTraitsService
     */
    private HumanTraitsService $humanTraitsService;

    public function __construct(
        ManagerRegistry $registry,
        TraitAnalysisRepository $traitAnalysisRepository,
        TraitItemRepository $traitItemRepository,
        TraitColorRepository $traitColorRepository,
        HumanTraitsService $humanTraitsService
    )
    {
        parent::__construct($registry, User::class);
        $this->traitAnalysisRepository = $traitAnalysisRepository;
        $this->traitItemRepository = $traitItemRepository;
        $this->traitColorRepository = $traitColorRepository;
        $this->humanTraitsService = $humanTraitsService;
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
            $user = $this->createQueryBuilder('u')
                ->andWhere('u.userId = :userId')
                ->setParameter('userId', $id)
                ->setMaxResults(1)
                ->getQuery()
                ->getSingleResult(AbstractQuery::HYDRATE_OBJECT);

            if ($user instanceof User) {
                $user = $this->applyAnalyses($user);
            }
            return $user;
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
     * @param string $keyword
     * @return array
     */
    public function searchForSubUser(string $keyword): array
    {
        try{
            return $this->createQueryBuilder('u')
                ->andWhere('LOWER(u.profile.firstName) = :keyword')
                ->orWhere('LOWER(u.profile.lastName) = :keyword')
                ->orWhere('LOWER(u.profile.email) = :keyword')
                ->setParameter('keyword', strtolower($keyword))
                ->getQuery()
                ->getResult();
        }
        catch (NoResultException) {
            return[];
        }
    }

    /**
     * Returns list of sub-users
     * @param string $myUserId
     * @return array
     */
    public function allSubUsers(string $myUserId): array
    {
        $result = [];
        $allSubUsers = $this->createQueryBuilder('u')
            ->andWhere('u.userAuthorId = :authorId')
            ->andWhere('u.roles LIKE :roles')
            ->setParameter('authorId', $myUserId)
            ->setParameter('roles', '%"' . User::ROLE_SUB_USER . '"%')
            ->getQuery()
            ->getResult(AbstractQuery::HYDRATE_OBJECT);

        foreach ($allSubUsers as $subUser) {
            if (!($subUser instanceof User)) {
                continue;
            }

            $subUser = $this->applyAnalyses($subUser);
            $result[] = $subUser;
        }
        return $result;
    }

    public function applyAnalyses(User $user): User
    {
        $analyses = $this->traitAnalysisRepository->findTraitsByBirthDay($user->profile->getBirthDay());
        $colorsReport = $this->humanTraitsService->schemaBuilder()->buildWorldsFromObject($analyses, $this->traitItemRepository, $this->traitColorRepository);
        $analysisReport =  $this->humanTraitsService->schemaBuilder()->buildTraitsFromObject($analyses, $this->traitItemRepository);

        $user->profile->setAnalysisReport($analysisReport);
        $user->profile->setColorsReport($colorsReport);
        return $user;
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

<?php

namespace App\Repository;

use App\Controller\API\User\Exceptions\UserAlreadyExistsException;
use App\Controller\API\User\Exceptions\UserRepeatedPasswordMatchingException;
use App\DAO\UserPackTObject;
use App\Entity\HumanTraits\TraitAnalysis;
use App\Entity\User\User;
use App\Entity\User\UserCompanyInfo;
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


    private int $startFrom = 0;
    private int $limit = 50;


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
     * @return int
     */
    public function getStartFrom(): int {
        return $this->startFrom;
    }

    /**
     * @param int|null $startFrom
     * @return $this
     */
    public function setStartFrom(?int $startFrom): self {
        if (!$startFrom){
            return $this;
        }
        $this->startFrom = $startFrom;
        return $this;
    }

    /**
     * @return int
     */
    public function getLimit(): int {
        return $this->limit;
    }

    /**
     * @param int|null $limit
     * @return $this
     */
    public function setLimit(?int $limit): self {
        if (!$limit){
            return $this;
        }
        $this->limit = $limit;
        return $this;
    }

    /**
     * @return QueryBuilder
     */
    private function userPackDTOMapper(): QueryBuilder
    {
        return  $this
            ->createQueryBuilder('user')
            ->select(sprintf(
                'NEW %s(
                 user.id, user.userId, user.userAuthorId, user.email, user.password, user.pwdRecoveryToken, user.roles,
                 profile.id, profile.firstName, profile.lastName, profile.email,  profile.avatar, profile.birthDay,
                 profile.placeOfBirth, profile.linksToProfiles, profile.notesDescriptionsComments,
                 profile.positionInTheCompany, profile.country,
                 company.companyName, company.companyWww, company.companyIndustry, company.wayToEarnMoney, company.regon,
                 company.krs, company.nip, company.districts, company.headQuartersCity, company.businessEmails, company.businessPhones,
                 company.revenue, company.profit, company.growthYearToYear, company.categories,
                 
                 analysis.id, analysis.birthDay, analysis.lifePath, analysis.theDrivingForce, analysis.theMatrixOfExellence, analysis.theMoralCode,
                 analysis.goalAndWants, analysis.behavioursAndNeeds, analysis.seekAndMindset, analysis.reactAndMotivationToAction, analysis.joinsAndDesire,
                 analysis.polarisation, analysis.expression, analysis.keyword, analysis.visualSeeItIntuition, analysis.auditoryHearItThinking,
                 analysis.kinestericDoItSensation, analysis.emotiveFeelItFeeling, analysis.initializingAndAntithesis, analysis.stabilizingAndSynthesis,
                 analysis.finishingThesis, analysis.doerControl, analysis.thinkerOrder, analysis.waterPeace, analysis.talkerFun, analysis.theValueOf, 
                 analysis.belief, analysis.communication, analysis.style, analysis.strength, analysis.reward, analysis.tactic, analysis.objective, 
                 analysis.worldOfAction, analysis.worldOfMatter, analysis.worldOfInformation, analysis.worldOfFeeling, analysis.worldOfFun, analysis.worldOfUsability,
                 analysis.worldOfRelations, analysis.worldOfDesireAndPower, analysis.worldOfSeekAndExplore, analysis.worldOfCareer, analysis.worldOfFuture, 
                 analysis.worldOfSpirituality, analysis.P1S, analysis.P2M, analysis.P3MY, analysis.P4W, analysis.P5M, analysis.P6J, analysis.P7S, analysis.P8U,
                 analysis.P9N, analysis.P10N, analysis.PTNde
                 
                 )',
                UserPackTObject::class
            ))
            ->innerJoin(UserProfile::class, 'profile', 'WITH', 'user.userId = profile.userId')
            ->leftJoin(UserCompanyInfo::class, 'company', 'WITH', 'user.userId = company.userId')
            ->leftJoin(TraitAnalysis::class, 'analysis', 'WITH', "DATE_FORMAT(profile.birthDay, '%d/%m/%Y') = DATE_FORMAT(cast(analysis.birthDay as date), '%d/%m/%Y')");
    }

    private function userPackDTOMapperApplyTraitsScheme(UserPackTObject $userPack): User {
        if (!is_null($userPack->user)) {

            $subUser = &$userPack->user;
            $colorsReport = $this->humanTraitsService->schemaBuilder()->buildWorldsFromObject($subUser->profile->traitAnalysis, $this->traitItemRepository, $this->traitColorRepository);
            $analysisReport =  $this->humanTraitsService->schemaBuilder()->buildTraitsFromObject($subUser->profile->traitAnalysis, $this->traitItemRepository);

            $subUser->profile->setAnalysisReport($analysisReport);
            $subUser->profile->setColorsReport($colorsReport);
            return $subUser;
        }
        return new User();
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
            return !!$this->getUserById($userId)->getUserId();
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
            return !!$this->getSubUserById($userId)->getUserId();
        }
        catch (Exception $ex){
            return false;
        }
    }
    /**
     * @param string $authorUserId
     * @param string $email
     * @return User
     */
    public function isMySubUser(string $authorUserId, string $email): User {
        try{
            return $this->createQueryBuilder('u')
                ->andWhere('LOWER(u.email) = :email')
                ->andWhere('u.userAuthorId = :userAuthorId')
                ->setParameter('email', strtolower($email))
                ->setParameter('userAuthorId', $authorUserId)
                ->setMaxResults(1)
                ->getQuery()
                ->getSingleResult();
        }
        catch (Exception $e) {
            return new User();
        }
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
     * @param string $email
     * @return User
     * @throws NonUniqueResultException
     */
    public function findUserByEmail(string $email): User
    {
        try{
            return $this->createQueryBuilder('user')
                ->andWhere('LOWER(user.email) = :email')
                ->andWhere('user.roles LIKE :roles')
                ->setParameter('email', strtolower($email))
                ->setParameter('roles', '%"' . User::ROLE_USER . '"%')
                ->setMaxResults(1)
                ->getQuery()
                ->getSingleResult();
        }
        catch (NoResultException) {
            return new User();
        }
    }

    /**
     * @param string $email
     * @return User
     * @throws NonUniqueResultException
     */
    public function findUserByEmailForAuth(string $email): User
    {
        try{
            return $this->createQueryBuilder('user')
                ->andWhere('LOWER(user.email) = :email')
                ->andWhere('user.roles LIKE :roles')
                ->andWhere('user.userAuthorId is null')
                ->setParameter('email', strtolower($email))
                ->setParameter('roles', '%"' . User::ROLE_USER . '"%')
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
    public function getUserById(string $userId) {
        return $this->createQueryBuilder('user')
            ->andWhere('user.userId = :userId')
            ->setParameter('userId', $userId)
            ->setMaxResults(1)
            ->getQuery()
            ->getSingleResult();
    }

    /**
     * @param string $subUserId
     * @return User
     */
    public function getSubUserById(string $subUserId): User {
         try {
             return $this->createQueryBuilder('user')
                 ->andWhere('user.roles LIKE :roles')
                 ->andWhere('user.userId = :subUserId')
                 ->setParameter('roles', '%"' . User::ROLE_SUB_USER . '"%')
                 ->setParameter('subUserId', $subUserId)
                 ->setMaxResults(1)
                 ->getQuery()
                 ->getSingleResult();
         }catch (NonUniqueResultException|NoResultException $ex) {
             return new User();
         }
    }


    /**
     * @param string $id
     * @return User
     * @throws NonUniqueResultException
     */
    public function findUserPackById(string $id): User
    {
        try{
            $user = $this->userPackDTOMapper()
                ->andWhere('user.userId = :userId')
                ->setParameter('userId', $id)
                ->setMaxResults(1)
                ->getQuery()
                ->getSingleResult(AbstractQuery::HYDRATE_OBJECT);

            if ($user instanceof UserPackTObject) {
                $user = $this->userPackDTOMapperApplyTraitsScheme($user);
            }
            return $user;
        }
        catch (NoResultException) {
            return new User();
        }
    }

    /**
     * @param string $subUserId
     * @return User
     * @throws NonUniqueResultException
     */
    public function getSubUserPackById(string $subUserId): User
    {
        try{
            $userPack = $this->userPackDTOMapper()
                    ->where('user.roles LIKE :roles')
                    ->andWhere('user.userId = :subUserId')
                    ->setParameter('roles', '%"' . User::ROLE_SUB_USER . '"%')
                    ->setParameter('subUserId', $subUserId)
                    ->setMaxResults(1)
                    ->getQuery()
                    ->getSingleResult();

            if ($userPack instanceof UserPackTObject) {
                return $this->userPackDTOMapperApplyTraitsScheme($userPack);
            }
            return new User();
        }
        catch (NoResultException) {
            return new User();
        }
    }



    /**
     * Returns list of sub-users
     * @param string $authorUserId
     * @return array
     */
    public function allSubUsers(string $authorUserId): array
    {

        $allSubUsers = $this->userPackDTOMapper()
            //->andWhere('user.userId = :authorId')
            ->andWhere('user.userAuthorId = :authorId')
            ->addOrderBy('user.userAuthorId', 'desc');
            //->andWhere('user.roles LIKE :roles');


        if ($this->startFrom !== -1) {
            $allSubUsers->setFirstResult($this->startFrom)->setMaxResults($this->limit);
        }

       $allSubUsers = $allSubUsers->addOrderBy('user.createdAt', 'DESC')
            ->setParameter('authorId', $authorUserId)
            //->setParameter('roles', '%"' . User::ROLE_SUB_USER . '"%')
            ->getQuery()
            ->getResult(AbstractQuery::HYDRATE_OBJECT);


        return array_map(function ($userPack){
           return $this->userPackDTOMapperApplyTraitsScheme($userPack);
        }, $allSubUsers);
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

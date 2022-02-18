<?php

namespace App\Tests;

use App\Entity\User\User;
use App\Entity\User\UserProfile;
use App\Repository\UserProfileRepository;
use App\Repository\UserRepository;
use App\Service\UserServices\AuthService\AuthService;
use DateTime;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use PHPUnit\Exception;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class UserTests extends KernelTestCase
{

    private EntityManager $entityManager;
    private AuthService $authService;

    protected function setUp(): void
    {
        $kernel = self::bootKernel();
        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();
    }

    /**
     * @throws OptimisticLockException
     * @throws ORMException
     */
    public function testCreateAccount()
    {
        $userId = '02eacbff-7e36-4d41-9d53-ac13150414e0';
        $user = new User();
        $repo = $this->getMockBuilder(UserRepository::class)->disableOriginalConstructor()->getMock();
        $user
            ->setUserId($userId)
            ->setEmail('levan.ostrowski@gmail.com')
            ->setPassword(password_hash('admin', PASSWORD_DEFAULT))
            ->setRoles([User::ROLE_USER])
            ->setCreatedAt();
        $repo->save($user);

        try {
            $this->assertNotNull($user);
        }
        catch (Exception $ex){
            echo $ex;
        }
    }

    /**
     * @throws OptimisticLockException
     * @throws ORMException
     * @throws \Exception
     */
    public function testCreateProfile()
    {
        $userId = '02eacbff-7e36-4d41-9d53-ac13150414e0';

        $repo = $this->getMockBuilder(UserProfileRepository::class)->disableOriginalConstructor()->getMock();
        $birthDate = '1998-11-17';
        $userProfile = new UserProfile();
        $userProfile
            ->setUserId($userId)
            ->setFirstName('Levan')
            ->setLastName('Ostrowski')
            ->setEmail('levan.ostrowski@gmail.com')
            ->setPhone('514380928')
            ->setBirthDay(new DateTime($birthDate))
            ->setCreatedAt();

        $repo->save($userProfile);

        try {
            $this->assertNotNull($userProfile);

        }
        catch (Exception $ex){
            echo $ex;
        }
    }
    /**
     * @throws NonUniqueResultException
     * @throws NoResultException
     */
    public function testFindAccount()
    {
        $userRepo = $this->entityManager->getRepository(User::class);
        if (!($userRepo instanceof UserRepository)){
            $this->assertEquals('1', '2');
        }

        $user = $userRepo->findUserPackById('1ec71241-781e-6486-8833-75000c6bd029');
        $profile = $user->profile->getFirstName();
        $this->assertNotNull($user);
    }

    public function testUpdateAccount()
    {
        $userRepo = $this->entityManager->getRepository(User::class);

        if (!($userRepo instanceof UserRepository)){
            $this->assertEquals('1', '2');
        }

        $user = $userRepo->findUserPackById('1ec71241-7a01-6bea-8e43-75000c6bd029');
         $this->assertNotNull($user);
    }
}

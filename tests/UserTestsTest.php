<?php

namespace App\Tests;

use App\Entity\User\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class UserTestsTest extends KernelTestCase
{

    private EntityManager $entityManager;

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
        $user = new User();
        $repo = $this->getMockBuilder(UserRepository::class)->disableOriginalConstructor()->getMock();
        $user
            ->setUserId(Uuid::uuid4()->toString())
            ->setEmail('levan.ostrowski@gmail.com')
            ->setPassword(password_hash('admin', PASSWORD_DEFAULT))
            ->setRoles([User::ROLE_USER])
            ->setCreatedAt();

        $repo->save($user);


    }
}

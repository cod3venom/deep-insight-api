<?php

namespace App\DataFixtures;

use App\Entity\User\User;
use App\Repository\UserRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Ramsey\Uuid\Uuid;

class UserFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $user = new User();
        $user
            ->setUserId(Uuid::uuid4()->toString())
            ->setEmail('levan.ostrowski@gmail.com')
            ->setPassword(password_hash('admin', PASSWORD_DEFAULT))
            ->setRoles([User::ROLE_USER, User::ROLE_ADMIN])
            ->setCreatedAt();

        $manager->persist($user);
        $manager->flush();
    }
}

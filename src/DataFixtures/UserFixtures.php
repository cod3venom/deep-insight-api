<?php

namespace App\DataFixtures;

use App\Entity\User\User;
use App\Entity\User\UserCompanyInfo;
use App\Entity\User\UserProfile;
use App\Repository\UserRepository;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Ramsey\Uuid\Uuid;

class UserFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $userid = 'f2881cd4-a02e-48dc-81bf-f1537a0b903f';
        $michalUserId = 'c7e2a147-8234-4af7-8c22-b686e5ba1e8a';

        $user = new User();
        $user->profile = new UserProfile();
        $user->company = new UserCompanyInfo();
        $user
            ->setUserId($userid)
            ->setEmail('levan.ostrowski@gmail.com')
            ->setPassword(password_hash('admin', PASSWORD_DEFAULT))
            ->setRoles([User::ROLE_USER, User::ROLE_ADMIN])
            ->setLastLoginAt()
            ->setCreatedAt();

        $user->profile
            ->setUserId($userid)
            ->setFirstName('Levan')
            ->setLastName('Ostrowski')
            ->setBirthDay(new DateTime('1998-11-17'))
            ->setEmail('levan.ostrowski@gmail.com')
            ->setPhone('514380928')
            ->setCreatedAt();;

        $user->company
            ->setUserId($userid)
            ->setCompanyName('Roots-Connector')
            ->setCompanyWww('www.rootsconnector.com')
            ->setCompanyIndustry('IT')
            ->setWayToEarnMoney('Want more? do more !!!')
            ->setRegon('12345')
            ->setKrs('54321')
            ->setNip('56789')
            ->setDistricts('LA, Birmingham')
            ->setHeadQuartersCity('England ....')
            ->setBusinessEmails('bussiness@rootsconnector.com')
            ->setBusinessPhones(
                '514380928, '.
                '514380929, '.
                '514380930, '.
                '514380931'
            )
            ->setRevenue('10000000')
            ->setProfit('5000000')
            ->setGrowthYearToYear('')
            ->setCategories('IT, Programming, Big Data, AI, Cyber Security')
            ->setCreatedAt();

        $manager->persist($user);
        $manager->flush();

        $user = new User();
        $user->profile = new UserProfile();
        $user->company = new UserCompanyInfo();
        $user
            ->setUserId($michalUserId)
            ->setEmail('michalwojda@gmail.com')
            ->setPassword(password_hash('admin', PASSWORD_DEFAULT))
            ->setRoles([User::ROLE_USER, User::ROLE_ADMIN])
            ->setLastLoginAt()
            ->setCreatedAt();

        $user->profile
            ->setUserId($michalUserId)
            ->setFirstName('Michał')
            ->setLastName('Wojda')
            ->setBirthDay(new DateTime('1998-11-17'))
            ->setEmail('michalwojda@gmail.com')
            ->setPhone('514380928')
            ->setCreatedAt();;

        $user->company
            ->setUserId($michalUserId)
            ->setCompanyName('Roots-Connector')
            ->setCompanyWww('www.rootsconnector.com')
            ->setCompanyIndustry('IT')
            ->setWayToEarnMoney('Want more? do more !!!')
            ->setRegon('12345')
            ->setKrs('54321')
            ->setNip('56789')
            ->setDistricts('LA, Birmingham')
            ->setHeadQuartersCity('England ....')
            ->setBusinessEmails('bussiness@rootsconnector.com')
            ->setBusinessPhones(
                '514380928, '.
                '514380929, '.
                '514380930, '.
                '514380931'
            )
            ->setRevenue('10000000')
            ->setProfit('5000000')
            ->setGrowthYearToYear('')
            ->setCategories('IT, Programming, Big Data, AI, Cyber Security')
            ->setCreatedAt();

        $manager->persist($user);
        $manager->flush();
    }
}

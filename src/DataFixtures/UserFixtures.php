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
        $user = new User();
        $companyInfo = new UserCompanyInfo();
        $user->profile = new UserProfile();
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

        $companyInfo
            ->setUserId($userid)
            ->setCompanyName('Roots-Connector')
            ->setCompanyWww('www.rootsconnector.com')
            ->setCompanyIndustry('IT')
            ->setWayToEarnMoney('Want more? do more !!!')
            ->setRegon(12345)
            ->setKrs(54321)
            ->setNip(56789)
            ->setDistricts([
                'Birminggram',
                'Warsaw',
                'Tokio'
            ])
            ->setHeadQuartersCity('England ....')
            ->setBusinessEmail('bussiness@rootsconnector.com')
            ->setBusinessPhones([
                '514380928',
                '514380929',
                '514380930',
                '514380931'
            ])
            ->setRevenue('10000000')
            ->setProfit('5000000')
            ->setGrowthYearToYear([
                '0' => [
                    '2017' => '21500',
                    '2018' => '5000000'
                ],
                '1' => [
                    '2019' => '21500',
                    '2020' => '5000000'
                ]
            ])
            ->setCategories([
                'IT',
                'Programming',
                'Big Data',
                'AI',
                'Cyber Security'
            ])
            ->setCreatedAt();

        $manager->persist($user);
        $manager->persist($user->profile);
        $manager->persist($companyInfo);
        $manager->flush();
    }
}

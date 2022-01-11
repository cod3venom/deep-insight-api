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

class SubUserFixtures extends Fixture
{
    private const emails = [
        'brad.lea@lightspeedvt.com',
        'sub.mark.zuckerberg@facebook.com',
        'donald@trump.com',
        'elon.musk@spacex.com',
        'jeff.bezos@amazon.com'
    ];

    private const names = [
        'Brad Lea',
        'Mark Zuckerberg',
        'Donald Trump',
        'Elon Musk',
        'Jeff bezos'
    ];

    private const companies = [
        'LightSpeed VT',
        'Meta',
        'Trump tower',
        'Space-x',
        'Amazon'
    ];


    /**
     * @throws \Exception
     */
    public function load(ObjectManager $manager): void
    {
        $authorId = 'f2881cd4-a02e-48dc-81bf-f1537a0b903f';

        for($i = 0; $i < count(self::emails); $i++)
        {
            $email = self::emails[$i];
            $name = explode(' ', self::names[$i])[0];
            $lastname = explode(' ', self::names[$i])[1];
            $company = self::companies[$i];

            $userid = Uuid::uuid4()->toString();
            $user = new User();
            $profile = new UserProfile();
            $companyInfo = new UserCompanyInfo();
            $user
                ->setUserId($userid)
                ->setUserAuthorId($authorId)
                ->setEmail($email)
                ->setPassword(password_hash('admin', PASSWORD_DEFAULT))
                ->setRoles([User::ROLE_SUB_USER])
                ->setLastLoginAt()
                ->setCreatedAt();


            $profile
                ->setUserId($user->getUserId())
                ->setFirstName($name)
                ->setLastName($lastname)
                ->setBirthDay(new DateTime('1998-11-'.$i))
                ->setEmail($email)
                ->setPhone('51438092'.$i)
                ->setCreatedAt();;

            $companyInfo
                ->setUserId($profile->getUserId())
                ->setCompanyName($company)
                ->setCompanyWww('www.'.str_replace(' ', '-', $company).'.com')
                ->setCompanyIndustry('IT')
                ->setWayToEarnMoney('Want more? do more !!!')
                ->setRegon(12345)
                ->setKrs(54321)
                ->setNip(56789)
                ->setDistricts([
                    'LA',
                ])
                ->setHeadQuartersCity('USA ....')
                ->setBusinessEmail('bussiness@'.str_replace(' ', '-', $company).'.com')
                ->setBusinessPhones([
                    '51438092'.$i,
                    '51438092'.$i,
                    '51438092'.$i,
                    '51438092'.$i,
                ])
                ->setRevenue('10000000')
                ->setProfit('5000000')
                ->setGrowthYearToYear([
                    '0' => [
                        '2017' => $i.'1500',
                        '2018' => $i.'5000000'
                    ],
                    '1' => [
                        '2019' => $i.'21500',
                        '2020' => $i.'5000000'
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
            $manager->persist($profile);
            $manager->persist($companyInfo);
            $manager->flush();
        }
    }
}

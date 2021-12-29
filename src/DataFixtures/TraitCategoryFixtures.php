<?php

namespace App\DataFixtures;

use App\Entity\HumanTraits\TraitCategory;
use App\Entity\User\User;
use App\Repository\UserRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Ramsey\Uuid\Uuid;

class TraitCategoryFixtures extends Fixture
{
    private const CATEGORIES = [
        'The hidden Driver',
        'The hidden Determinations',
        'The hidden Factors',
        'The hidden Memorizing (Learning | Sense | Perception)',
        'Stage in the process',
        'Temperament',
        'The hidden Spheres of Life',
        'Worlds of',
        'Details'
    ];

    public function load(ObjectManager $manager): void
    {
        for($i = 0; $i < count(self::CATEGORIES); $i ++) {
            $catObj = new TraitCategory();
            $catObj
                ->setCategoryId(Uuid::uuid4()->toString())
                ->setName(self::CATEGORIES[$i])
                ->setPosition($i)
                ->setCreatedAt();

            $manager->persist($catObj);
        }
        $manager->flush();
    }
}

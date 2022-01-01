<?php

namespace App\DataFixtures;

use App\Entity\HumanTraits\TraitCategory;
use App\Entity\HumanTraits\TraitColor;
use App\Entity\HumanTraits\TraitItem;
use App\Entity\User\User;
use App\Repository\TraitCategoryRepository;
use App\Repository\UserRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Ramsey\Uuid\Uuid;

class HumanTraitFixtures extends Fixture
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

    private const CATEGORIES_MAP = [
        'The hidden Driver' => [
            'Life Path',
            'The Driving Force',
            'The Matrix of Excellence',
            'The Moral Code'
        ],
        'The hidden Determinations' => [
            'Goals & Wants',
            'Behaviors & Needs',
            'Seeks & Mindset',
            'React & Motive to Action',
            'Joins & Desire'
        ],
        'The hidden Factors' => [
            'Polarisation',
            'Expression'
        ],
        'The hidden Memorizing (Learning | Sense | Perception)' => [
            'Visual | See it | Intuition',
            'Kinesteric | Do it | Sensation',
            'Auditory | Hear it | Thinking',
            'Emotive',
        ],
        'Stage in the process' => [
            'initializing & antithesis',
            'stabilizing & synthesis',
            'finishing & thesis',
        ],
        'Temperament' => [
            'Doer - Control',
            'Thinker - Order',
            'Water - Peace',
            'Talker - Fun'
        ],
        'The hidden Spheres of Life' => [
            'The Value of',
            'Belief',
            'Communication',
            'Style',
            'Strength',
            'Reward',
            'Tactic',
            'Objective',
        ],
        'Worlds of' => [
            'World of Action',
            'World of Matter',
            'World of Information',
            'World of Feeling',
            'World of Fun',
            'World of Usability',
            'World of Relations',
            'World of Desire&Power',
            'World of Career',
            'World of Future',
            'World of Spirituality'
        ],
        'Details' => [
            'P1S',
            'P2M',
            'P3MY',
            'P4W',
            'P5M',
            'P6J',
            'P7S',
            'P8U',
            'P9N',
            'P10N',
            'PTNde'
        ]
    ];
    private const COLORS = [
        'World of Action' => '#F5202B',
        'World of Matter' => '#FF541C',
        'World of Information' => '#FF8E2E',
        'World of Feeling' => '#FFAE30',
        'World of Fun' => '#FFE119',
        'World of Usability' => '#92FF0C',
        'World of Relations' => '#00E859',
        'World of Desire&Power' => '#0EB89E',
        'World of Seek&Explore' => '#2A44F7',
        'World of Career' => '#1A12B5',
        'World of Future' => '#9027C4',
        'World of Spirituality' => '#B5A0FF'
    ];

    private TraitCategoryRepository $catRep;
    public function __construct(TraitCategoryRepository $catRep)
    {
        $this->catRep = $catRep;
    }

    public function load(ObjectManager $manager): void
    {
        for($i = 0; $i < count(self::CATEGORIES); $i ++) {
            $catObj = new TraitCategory();
            $catObj
                ->setCategoryName(self::CATEGORIES[$i])
                ->setPosition($i)
                ->setCreatedAt();

            $manager->persist($catObj);
            $manager->flush();
        }
        $this->loadItems($manager);
    }

    private function loadItems(ObjectManager $manager)
    {
        foreach (self::CATEGORIES_MAP as $k=>$v) {
            $name = $k;
            $items = $v;
            $category = $this->catRep->findByName($name);

            foreach ($items as $item) {
                $traitItem = new TraitItem();
                $traitItem
                    ->setCategoryId($category->getId())
                    ->setName($item)
                    ->setDataType('test')
                    ->setIcon('')
                    ->setCreatedAt();

                $manager->persist($traitItem);
                $manager->flush();
            }
        }

        $this->loadColors($manager);
    }

    private function loadColors(ObjectManager $objectManager)
    {
        foreach (self::COLORS as $name => $color) {
            $traitColor = new TraitColor();
            $traitColor->setName($name);
            $traitColor->setColor($color);
            $traitColor->setCreatedAt();;
            $objectManager->persist($traitColor);
            $objectManager->flush();
        }
    }
}

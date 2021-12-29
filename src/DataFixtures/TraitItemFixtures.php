<?php

namespace App\DataFixtures;

use App\Entity\HumanTraits\TraitCategory;
use App\Entity\User\User;
use App\Repository\TraitCategoryRepository;
use App\Repository\UserRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Ramsey\Uuid\Uuid;

class TraitItemFixtures extends Fixture
{
    private const CATEGORIES = [
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
            'Wather - Peace',
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

    private TraitCategoryRepository $catRep;
    public function __construct(TraitCategoryRepository $catRep)
    {
        $this->catRep = $catRep;
    }

    public function load(ObjectManager $manager): void
    {
        $res = $this->catRep->findByName('Details');

        foreach (self::CATEGORIES as $k=>$v) {
            $name = $k;
            $items = $v;
            echo $name;
        }
        //$manager->flush();
    }
}

<?php

namespace App\Tests;

use App\Entity\HumanTraits\TraitCategory;
use App\Entity\HumanTraits\TraitItem;
use Doctrine\ORM\EntityManager;
use PHPUnit\Framework\TestCase;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class TraitItemTest extends KernelTestCase
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

    /**
     * @var EntityManager
     */
    private $entityManager;

    protected function setUp(): void
    {
        $kernel = self::bootKernel();

        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();
    }

    public function testMapItemTOCategory(): void
    {
        $repo = $this->entityManager->getRepository(TraitCategory::class);
        foreach (self::CATEGORIES as $k=>$v) {
            $name = $k;
            $items = $v;
            $category = $repo->findByName($name);

            foreach ($items as $item) {
                $traitItem = new TraitItem();
                $traitItem
                    ->setCategoryId($category->getCategoryId())
                    ->setName($item)
                    ->setDataType('test')
                    ->setIcon('')
                    ->setCreatedAt();

                $repo->save($traitItem);
            }
        }
    }

    public function testReadAllItems()
    {
        $repo = $this->entityManager->getRepository(TraitItem::class);

        $read = $repo->all();

        print_r($read);
    }

}

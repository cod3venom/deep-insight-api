<?php

namespace App\Tests;

use App\Entity\HumanTraits\TraitCategory;
use App\Repository\TraitCategoryRepository;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class TraitCategoryTest  extends KernelTestCase
{

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

    public function testLookupCategoryByName(): void
    {
        $name = 'The hidden Memorizing (Learning | Sense | Perception)';
        $rep = $this->entityManager->getRepository(TraitCategory::class);
        $result = $rep->findByName($name);
        var_dump($result->getCategoryId());
    }

    public function testCategoryAndItems()
    {
        $rep = $this->entityManager->getRepository(TraitCategory::class);
        $result = $rep->findAll();
        print_r($result);
    }

    public function testGetAllCategoryNames()
    {
        $rep = $this->entityManager->getRepository(TraitCategory::class);

        $result = $rep->getAllCategoryNames();
        print_r($result);
    }
}

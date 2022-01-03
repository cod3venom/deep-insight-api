<?php

namespace App\Tests;

use App\Entity\HumanTraits\TraitAnalysis;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class TraitAnalysisTest extends KernelTestCase
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
    public function testGetAll()
    {
        $repo = $this->entityManager->getRepository(TraitAnalysis::class);
        $all = $repo->findAll();

        print_r($all);
    }
}

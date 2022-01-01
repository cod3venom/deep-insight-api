<?php

namespace App\Tests;

use App\Entity\HumanTraits\TraitCategory;
use App\Entity\HumanTraits\TraitItem;
use Doctrine\ORM\EntityManager;
use PHPUnit\Framework\TestCase;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class TraitItemTest extends KernelTestCase
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



    public function testReadAllItems()
    {
        $repo = $this->entityManager->getRepository(TraitItem::class);

        $items = $repo->all();
        foreach ($items as $item) {
            $type = gettype($item);
            if (!($item instanceof TraitItem)){
                continue;
            }

//            $name = $item->getCategoryName();
//            echo $name;
        }


    }

}

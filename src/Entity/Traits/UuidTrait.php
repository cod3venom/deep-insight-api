<?php

declare(strict_types=1);

namespace App\Entity\Traits;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\IdGenerator\UuidGenerator;

/**
 * Class IdTrait
 * @package App\Entity\Traits
 */
trait UuidTrait
{
    /**
     * @ORM\Id
     * @ORM\Column(type="uuid", unique=true)
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class=UuidGenerator::class)
     * @var string
     */
    protected string $id;

    /**
     * Sometimes we need to parse&assign
     * id to the user en
     */
    public function setId(string $uuid)
    {
        $this->id = $uuid;
    }
    public function getId(): string
    {
        return $this->id;
    }
}

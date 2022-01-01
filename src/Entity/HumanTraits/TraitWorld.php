<?php

namespace App\Entity\HumanTraits;

use App\Entity\Traits\CreatedTrait;
use App\Entity\Traits\UpdatedTrait;
use App\Repository\TraitWorldRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TraitWorldRepository::class)
 */
class TraitWorld
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $worldName;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $color;

    use UpdatedTrait;
    use CreatedTrait;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getWorldName(): ?string
    {
        return $this->worldName;
    }

    public function setWorldName(string $worldName): self
    {
        $this->worldName = $worldName;

        return $this;
    }

    public function getColor(): ?string
    {
        return $this->color;
    }

    public function setColor(string $color): self
    {
        $this->color = $color;

        return $this;
    }
}

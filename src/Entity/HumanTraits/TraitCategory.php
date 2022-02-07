<?php

namespace App\Entity\HumanTraits;

use App\Entity\Traits\CreatedTrait;
use App\Entity\Traits\UpdatedTrait;
use App\Repository\TraitCategoryRepository;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;

/**
 * @ORM\Entity(repositoryClass=TraitCategoryRepository::class)
 */
class TraitCategory
{
    /**
     * @ORM\GeneratedValue
     * @ORM\Id
     * @ORM\Column(type="integer")
     */
    private string $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $categoryName;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private ?string $position;

    use UpdatedTrait;
    use CreatedTrait;




    public function getId(): int
    {
        return $this->id;
    }

    public function getCategoryName(): ?string
    {
        return $this->categoryName;
    }

    public function setCategoryName(string $name): self
    {
        $this->categoryName = $name;

        return $this;
    }

    public function getPosition (): int
    {
        return $this->position;
    }

    public function setPosition(int $position): self
    {
        $this->position = $position;

        return $this;
    }

    public function __toString(): string
    {
        return $this->categoryName;
    }
}

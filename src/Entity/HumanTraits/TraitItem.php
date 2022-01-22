<?php

namespace App\Entity\HumanTraits;

use App\Entity\Traits\CreatedTrait;
use App\Entity\Traits\UpdatedTrait;
use App\Entity\HumanTraits\TraitCategory;
use App\Repository\TraitItemRepository;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Rfc4122\UuidInterface;
use Ramsey\Uuid\Uuid;

/**
 * @ORM\Entity(repositoryClass=TraitItemRepository::class)
 */
class TraitItem
{
    /**
     * @ORM\Column(type="string", length=255)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private string $name;

    /**
     * @ORM\Column(type="integer")
     **/
    private string $categoryId;

    /**
     * @ORM\Column(type="string", length=10)
     */
    private string $dataType;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $icon;

    use UpdatedTrait;
    use CreatedTrait;

    public function __construct()
    {
        $this->categoryId = Uuid::uuid4();
    }

    public function getCategoryId(): UuidInterface|string
    {
        return $this->categoryId;
    }

    public function setCategoryId(int $categoryId): self
    {
        $this->categoryId = $categoryId;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getIcon(): ?string
    {
        return $this->icon;
    }

    public function setIcon(?string $icon): self
    {
        $this->icon = $icon;

        return $this;
    }

    public function getDataType(): ?string
    {
        return $this->dataType;
    }

    public function setDataType(string $dataType): self
    {
        $this->dataType = $dataType;

        return $this;
    }
}

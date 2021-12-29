<?php

declare(strict_types=1);

namespace App\Entity\Traits;

use DateTime;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class CreatedTrait
 * @package App\Entity\Traits
 */
trait CreatedTrait
{
    /**
     * @ORM\Column(type="datetime", options={"default": "CURRENT_TIMESTAMP"})
     * @var ?DateTimeInterface
     */
    protected ?DateTimeInterface $createdAt;

    /**
     * Set createdAt
     * @ORM\PrePersist
     */
    public function setCreatedAt()
    {
        $this->createdAt  = new DateTime();

        if (property_exists($this, 'updatedAt')) {
            $this->updatedAt  = new DateTime();
        }
    }

    /**
     * Get createdAt
     * @return ?DateTimeInterface
     */
    public function getCreatedAt(): ?DateTimeInterface
    {
        return $this->createdAt;
    }
}
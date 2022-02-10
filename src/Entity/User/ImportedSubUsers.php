<?php

namespace App\Entity\User;

use App\Entity\Traits\IdTrait;
use App\Repository\ImportedSubUsersRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ImportedSubUsersRepository::class)
 */
class ImportedSubUsers
{
    use IdTrait;

    public const IMPORTED_FROM_FILE = 'file';
    public const IMPORTED_FROM_LINKEDIN = 'linkedin';
    public const IMPORTED_FROM_PHONE = 'phone';

    /**
     * @ORM\Column(type="uuid")
     */
    private string $userId;

    /**
     * @ORM\Column(type="uuid")
     */
    private string $userAuthorId;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $source;

    /**
     * @ORM\Column(type="string", length=255)
     */
    public string $sourceType;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserId(): string
    {
        return $this->userId;
    }

    public function setUserId($userId): self
    {
        $this->userId = $userId;

        return $this;
    }

    public function getUserAuthorId(): string
    {
        return $this->userAuthorId;
    }

    public function setUserAuthorId($userAuthorId): self
    {
        $this->userAuthorId = $userAuthorId;

        return $this;
    }

    public function getSource(): ?string
    {
        return $this->source;
    }

    public function setSource(string $source): self
    {
        $this->source = $source;

        return $this;
    }

    public function getSourceType(): ?string
    {
        return $this->sourceType;
    }

    public function setSourceType(string $sourceType): self
    {
        $this->sourceType = $sourceType;

        return $this;
    }
}

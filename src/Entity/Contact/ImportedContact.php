<?php

namespace App\Entity\Contact;

use App\Entity\Traits\IdTrait;
use App\Repository\ImportedContactRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ImportedSubUsersRepository::class)
 */
class ImportedContact
{
    use IdTrait;

    public const IMPORTED_FROM_FILE = 'file';
    public const IMPORTED_FROM_LINKEDIN = 'linkedin';
    public const IMPORTED_FROM_PHONE = 'phone';

    /**
     * @ORM\Column(type="uuid")
     */
    private string $contactId;

    /**
     * @ORM\Column(type="uuid")
     */
    private string $ownerUserId;

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

    public function getContactId(): string
    {
        return $this->contactId;
    }

    public function setContactId($contactId): self
    {
        $this->contactId = $contactId;

        return $this;
    }

    public function getUserOwnerId(): string
    {
        return $this->ownerUserId;
    }

    public function setUserOwnerId($ownerUserId): self
    {
        $this->ownerUserId = $ownerUserId;

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
